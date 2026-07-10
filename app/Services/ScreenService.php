<?php

namespace App\Services;

use App\Models\Cinema;
use App\Models\User;
use App\Repositories\Interfaces\ScreenRepositoryInterface;
use App\Repositories\Interfaces\SeatCategoryRepositoryInterface;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class ScreenService
{
    public function __construct(
        protected ScreenRepositoryInterface $repository,
        protected SeatCategoryRepositoryInterface $seatCategoryRepository,
        protected SeatRepositoryInterface $seatRepository,

    ) {}

    public function index(array $filters, User $user)
    {
        if ($user->hasRole('company_admin')) {
            $filters['company_id'] = $user->company_id;
        }

        if ($user->hasRole('cinema_admin')) {
            $filters['cinema_id'] = $user->cinema_id;
        }

        return $this->repository->index($filters);
    }

    public function find(int $id, User $user)
    {
        $screen = $this->repository->find($id);

        $this->authorize($screen->cinema, $user);

        return $screen;
    }



    public function create(array $data, User $user)
    {
        $this->validateCinema($data['cinema_id'], $user);

        return DB::transaction(function () use ($data) {

            // 1. Create Screen
            $screen = $this->repository->create([
                'cinema_id' => $data['cinema_id'],
                'name' => $data['name'],
                'screen_type' => $data['screen_type'],
                'capacity'    => $data['capacity'],
                'status' => $data['status'] ?? 'active',
            ]);

            // 2. Create Seat Categories
            foreach ($data['seat_categories'] as $category) {

                $seatCategory = $this->seatCategoryRepository->create([
                    'screen_id' => $screen->id,
                    'name' => $category['name'],
                    'image_icon' => $category['image_icon'] ?? null,

                    'display_order' => $category['display_order'] ?? 1,

                ]);

                // 3. Create Seats
                foreach ($category['seats'] as $seat) {

                    $this->seatRepository->create([
                        'screen_id'   => $screen->id,
                        'category_id' => $seatCategory->id,
                        'row_label'   => $seat['row_label'],
                        'seat_number' => $seat['seat_number'],
                        'seat_label'  => $seat['seat_label'] ?? ($seat['row_label'] . $seat['seat_number']),
                        'pos_x'       => $seat['pos_x'] ?? null,
                        'pos_y'       => $seat['pos_y'] ?? null,
                        'status'      => true,
                    ]);
                }
            }

            return $screen->load('seatCategories.seats');
        });
    }

    public function update(int $id, array $data, User $user)
    {
        $screen = $this->repository->find($id);

        $this->authorize($screen->cinema, $user);

        return DB::transaction(function () use ($screen, $data) {

            // 1. Update screen
            $this->repository->update($screen->id, [
                'cinema_id'   => $data['cinema_id'],
                'name'        => $data['name'],
                'screen_type' => $data['screen_type'],
                'capacity'    => $data['capacity'],
                'status'      => $data['status'] ?? $screen->status,
            ]);

            // Reload updated screen
            $screen = $this->repository->find($screen->id);

           

            foreach ($screen->seatCategories as $category) {

                // Delete seats
                $category->seats()->delete();

                // Delete category
                $category->delete();
            }

           

            foreach ($data['seat_categories'] as $categoryData) {

                $seatCategory = $this->seatCategoryRepository->create([
                    'screen_id'     => $screen->id,
                    'name'          => $categoryData['name'],
                    'image_icon'    => $categoryData['image_icon'] ?? null,
                    'display_order' => $categoryData['display_order'] ?? 1,
                    'price'         => $categoryData['price'] ?? 0,
                ]);

                foreach ($categoryData['seats'] as $seatData) {

                    $this->seatRepository->create([
                        'screen_id'   => $screen->id,
                        'category_id' => $seatCategory->id,
                        'row_label'   => $seatData['row_label'],
                        'seat_number' => $seatData['seat_number'],
                        'seat_label'  => $seatData['seat_label']
                            ?? $seatData['row_label'] . $seatData['seat_number'],
                        'pos_x'       => $seatData['pos_x'] ?? null,
                        'pos_y'       => $seatData['pos_y'] ?? null,
                        'status'      => true,
                    ]);
                }
            }

            return $screen->fresh()->load('seatCategories.seats');
        });
    }

    public function delete(int $id, User $user)
    {
        $screen = $this->repository->find($id);

        $this->authorize($screen->cinema, $user);

        return $this->repository->delete($id);
    }

    private function validateCinema(int $cinemaId, User $user): void
    {
        $query = Cinema::where('id', $cinemaId);

        if ($user->hasRole('company_admin')) {
            $query->where('company_id', $user->company_id);
        }

        if ($user->hasRole('cinema_admin')) {
            $query->where('id', $user->cinema_id);
        }

        $query->firstOrFail();
    }

    private function authorize(Cinema $cinema, User $user): void
    {
        if (
            $user->hasRole('company_admin') &&
            $user->company_id !== $cinema->company_id
        ) {
            throw new AuthorizationException(
                'You cannot manage screens outside your company.'
            );
        }

        if (
            $user->hasRole('cinema_admin') &&
            $user->cinema_id !== $cinema->id
        ) {
            throw new AuthorizationException(
                'You cannot manage screens for another cinema.'
            );
        }
    }
}
