<?php

namespace App\Services;

use App\Models\Screen;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SeatService
{
    public function __construct(
        protected SeatRepositoryInterface $repository
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
        $seat = $this->repository->find($id);

        $this->authorizeScreen($seat->screen);

        return $seat;
    }

    public function store(array $data)
    {
        $screen = Screen::findOrFail($data['screen_id']);

        $this->authorizeScreen($screen);

       if (
    empty($data['seat_label']) &&
    isset($data['row_label'], $data['seat_number'])
) {
    $data['seat_label'] = $data['row_label'] . $data['seat_number'];
}

        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        $seat = $this->repository->find($id);

        $this->authorizeScreen($seat->screen);

        if (
            isset($data['row_label']) ||
            isset($data['seat_number'])
        ) {
            $data['seat_label'] =
                ($data['row_label'] ?? $seat->row_label) .
                ($data['seat_number'] ?? $seat->seat_number);
        }

        return $this->repository->update($id, $data);
    }

    public function destroy(int $id)
    {
        $seat = $this->repository->find($id);

        $this->authorizeScreen($seat->screen);

        return $this->repository->delete($id);
    }

    private function authorizeScreen(Screen $screen): void
    {
        $user = Auth::user();

        if (
            $user->hasRole('company_admin') &&
            $screen->company_id != $user->company_id
        ) {
            throw new AccessDeniedHttpException(
                'You cannot manage seats outside your company.'
            );
        }
    }
}