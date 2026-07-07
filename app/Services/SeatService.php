<?php

namespace App\Services;

use App\Models\Screen;
use App\Models\User;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SeatService
{
    public function __construct(
        protected SeatRepositoryInterface $repository
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

    public function show(int $id, User $user)
    {
        $seat = $this->repository->find($id);

        $seat->load('screen.cinema');

        $this->authorizeScreen($seat->screen, $user);

        return $seat;
    }

    public function store(array $data, User $user)
    {
        $screen = Screen::with('cinema')->findOrFail($data['screen_id']);

        $this->authorizeScreen($screen, $user);

        if (empty($data['seat_label']) &&
            isset($data['row_label'], $data['seat_number'])) {
            $data['seat_label'] = $data['row_label'] . $data['seat_number'];
        }

        $existingSeat = $this->repository->findByPosition(
            $data['screen_id'],
            $data['row_label'],
            $data['seat_number']
        );

        if ($existingSeat) {
            throw ValidationException::withMessages([
                'seat' => "A seat with row '{$data['row_label']}' and number '{$data['seat_number']}' already exists in this screen."
            ]);
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, User $user)
    {
        $seat = $this->repository->find($id);

        $seat->load('screen.cinema');

        $this->authorizeScreen($seat->screen, $user);

        if (isset($data['row_label']) || isset($data['seat_number'])) {

            $newRowLabel = $data['row_label'] ?? $seat->row_label;
            $newSeatNumber = $data['seat_number'] ?? $seat->seat_number;

            $existingSeat = $this->repository->findByPosition(
                $seat->screen_id,
                $newRowLabel,
                $newSeatNumber
            );

            if ($existingSeat && $existingSeat->id != $seat->id) {
                throw ValidationException::withMessages([
                    'seat' => "A seat with row '{$newRowLabel}' and number '{$newSeatNumber}' already exists in this screen."
                ]);
            }

            $data['seat_label'] = $newRowLabel . $newSeatNumber;
        }

        return $this->repository->update($id, $data);
    }

    public function destroy(int $id, User $user)
    {
        $seat = $this->repository->find($id);

        $seat->load('screen.cinema');

        $this->authorizeScreen($seat->screen, $user);

        return $this->repository->delete($id);
    }

    private function authorizeScreen(Screen $screen, User $user): void
    {
        if (
            $user->hasRole('company_admin') &&
            $screen->cinema->company_id !== $user->company_id
        ) {
            throw new AccessDeniedHttpException(
                'You cannot manage seats outside your company.'
            );
        }

        if (
            $user->hasRole('cinema_admin') &&
            $screen->cinema_id !== $user->cinema_id
        ) {
            throw new AccessDeniedHttpException(
                'You cannot manage seats outside your cinema.'
            );
        }
    }
}