<?php

namespace App\Services;

use App\Models\Screen;
use App\Repositories\Interfaces\SeatCategoryRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SeatCategoryService
{
    public function __construct(
        protected SeatCategoryRepositoryInterface $repository
    ) {}

    public function index(array $filters = [])
    {
        $user = Auth::user();

        if ($user->hasRole('company_admin')) {
            $filters['company_id'] = $user->company_id;
        }

        return $this->repository->index($filters);
    }

    public function show(int $id)
    {
        $seatCategory = $this->repository->find($id);

        $this->authorizeScreen($seatCategory->screen);

        return $seatCategory;
    }

    public function store(array $data)
    {
        $screen = Screen::findOrFail($data['screen_id']);

        $this->authorizeScreen($screen);

        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        $seatCategory = $this->repository->find($id);

        $this->authorizeScreen($seatCategory->screen);

        return $this->repository->update($id, $data);
    }

    public function destroy(int $id)
    {
        $seatCategory = $this->repository->find($id);

        $this->authorizeScreen($seatCategory->screen);

        return $this->repository->delete($id);
    }

    private function authorizeScreen(Screen $screen): void
    {
        $user = Auth::user();

        if ($user->hasRole('company_admin') && $screen->company_id != $user->company_id) {
            throw new AccessDeniedHttpException(
                'You cannot manage seat categories outside your company.'
            );
        }
    }
}
