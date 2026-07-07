<?php

namespace App\Services;

use App\Models\Screen;
use App\Models\User;
use App\Repositories\Interfaces\SeatCategoryRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SeatCategoryService
{
    public function __construct(
        protected SeatCategoryRepositoryInterface $repository
    ) {}

    public function index(array $filters, User $user)
    {
        if ($user->hasRole('branch_manager')) {
            $filters['company_id'] = $user->company_id;
            $filters['cinema_id'] = $user->cinema_id;
        }

        return $this->repository->index($filters);
    }


    public function show(int $id, User $user)
    {
        $seatCategory = $this->repository->find($id);

        $seatCategory->load('screen.cinema');

        $this->authorizeScreen($seatCategory->screen, $user);

        return $seatCategory;
    }


    public function store(array $data, User $user)
    {
        $screen = Screen::with('cinema')
            ->findOrFail($data['screen_id']);

        $this->authorizeScreen($screen, $user);

        return $this->repository->create($data);
    }


    public function update(int $id, array $data, User $user)
    {
        $seatCategory = $this->repository->find($id);

        $seatCategory->load('screen.cinema');

        $this->authorizeScreen($seatCategory->screen, $user);

        return $this->repository->update($id, $data);
    }


    public function destroy(int $id, User $user)
    {
        $seatCategory = $this->repository->find($id);

        $seatCategory->load('screen.cinema');

        $this->authorizeScreen($seatCategory->screen, $user);

        return $this->repository->delete($id);
    }


    private function authorizeScreen(Screen $screen, User $user): void
    {
        $screen->loadMissing('cinema');

        if (
            $user->hasRole('branch_manager') &&
            (
                $screen->cinema->company_id !== $user->company_id ||
                $screen->cinema_id !== $user->cinema_id
            )
        ) {
            throw new AccessDeniedHttpException(
                'You cannot manage seat categories outside your assigned cinema.'
            );
        }
    }
}