<?php

namespace App\Services;

use App\Models\Cinema;
use App\Models\User;
use App\Repositories\Interfaces\ScreenRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;

class ScreenService
{
    public function __construct(
        protected ScreenRepositoryInterface $repository
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

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, User $user)
    {
        $screen = $this->repository->find($id);

        $this->authorize($screen->cinema, $user);

        return $this->repository->update($id, $data);
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