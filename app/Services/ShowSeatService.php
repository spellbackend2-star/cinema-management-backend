<?php

namespace App\Services;

use App\Models\ShowSeat;
use App\Repositories\Interfaces\ShowSeatRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ShowSeatService
{
    public function __construct(
        protected ShowSeatRepositoryInterface $showSeatRepository
    ) {}

    public function index(array $filters = [])
    {
        return $this->showSeatRepository->getAll(
            $filters['per_page'] ?? 10
        );
    }

    public function store(array $data)
    {
        return $this->showSeatRepository->create($data);
    }

    public function show(int $id)
    {
        $seat = $this->showSeatRepository->findById($id);

        if (!$seat) {
            throw new ModelNotFoundException('Show seat not found.');
        }

        return $seat;
    }

    public function update(int $id, array $data)
    {
        $seat = $this->showSeatRepository->findById($id);

        if (!$seat) {
            throw new ModelNotFoundException('Show seat not found.');
        }

        $this->showSeatRepository->update($seat, $data);

        return $seat->fresh();
    }

    public function destroy(int $id): void
    {
        $seat = $this->showSeatRepository->findById($id);

        if (!$seat) {
            throw new ModelNotFoundException('Show seat not found.');
        }

        $this->showSeatRepository->delete($seat);
    }

    public function lockSeat(int $showSeatId, int $userId): ShowSeat
    {
        return DB::transaction(function () use ($showSeatId, $userId) {

            $seat = $this->showSeatRepository->findForUpdate($showSeatId);

            if (!$seat) {
                throw new ModelNotFoundException('Seat not found.');
            }

            $this->releaseExpiredLock($seat);

            if ($seat->status === ShowSeat::BOOKED) {
                throw new ConflictHttpException('Seat already booked.');
            }

            if ($seat->status === ShowSeat::BLOCKED) {
                throw new ConflictHttpException('Seat is blocked.');
            }

            if (
                $seat->status === ShowSeat::LOCKED &&
                $seat->locked_by !== $userId
            ) {
                throw new ConflictHttpException(
                    'Seat is already locked by another customer.'
                );
            }

            $this->showSeatRepository->update($seat, [
                'status' => ShowSeat::LOCKED,
                'locked_by' => $userId,
                'locked_until' => now()->addMinutes(
                    config('booking.lock_minutes', 5)
                ),
            ]);

            return $seat->fresh();

        }, 3);
    }

    // public function unlockSeat(int $showSeatId): ShowSeat
    // {
    //     return DB::transaction(function () use ($showSeatId) {

    //         $seat = $this->showSeatRepository->findForUpdate($showSeatId);

    //         if (!$seat) {
    //             throw new ModelNotFoundException('Seat not found.');
    //         }

    //         $this->showSeatRepository->update($seat, [
    //             'status' => ShowSeat::AVAILABLE,
    //             'locked_by' => null,
    //             'locked_until' => null,
    //         ]);

    //         return $seat->fresh();

    //     });
    // }

    public function bookSeat(int $showSeatId, int $userId): ShowSeat
    {
        return DB::transaction(function () use ($showSeatId, $userId) {

            $seat = $this->showSeatRepository->findForUpdate($showSeatId);

            if (!$seat) {
                throw new ModelNotFoundException('Seat not found.');
            }

            if (
                $seat->status !== ShowSeat::LOCKED ||
                $seat->locked_by !== $userId
            ) {
                throw new ConflictHttpException(
                    'Seat must be locked by you before booking.'
                );
            }

            if (
                $seat->locked_until &&
                Carbon::parse($seat->locked_until)->isPast()
            ) {
                throw new ConflictHttpException(
                    'Seat lock has expired.'
                );
            }

            $this->showSeatRepository->update($seat, [
                'status' => ShowSeat::BOOKED,
                'locked_by' => null,
                'locked_until' => null,
            ]);

            return $seat->fresh();

        });
    }

    private function releaseExpiredLock(ShowSeat $seat): void
    {
        if (
            $seat->status === ShowSeat::LOCKED &&
            $seat->locked_until &&
            Carbon::parse($seat->locked_until)->isPast()
        ) {
            $this->showSeatRepository->update($seat, [
                'status' => ShowSeat::AVAILABLE,
                'locked_by' => null,
                'locked_until' => null,
            ]);

            $seat->refresh();
        }
    }
}