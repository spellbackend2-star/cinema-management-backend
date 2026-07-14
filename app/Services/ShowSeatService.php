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
    private const LOCK_MINUTES = 5;

    public function __construct(
        protected ShowSeatRepositoryInterface $showSeatRepository
    ) {}

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    public function index(array $filters = [])
    {
        return $this->showSeatRepository->getAll(
            $filters['per_page'] ?? 10
        );
    }

    public function store(array $data): ShowSeat
    {
        return $this->showSeatRepository->create($data);
    }

    public function show(int $id): ShowSeat
    {
        return $this->findSeatOrFail($id);
    }

    public function update(int $id, array $data): ShowSeat
    {
        $seat = $this->findSeatOrFail($id);

        $this->showSeatRepository->update($seat, $data);

        return $seat->fresh();
    }

    public function destroy(int $id): void
    {
        $seat = $this->findSeatOrFail($id);

        $this->showSeatRepository->delete($seat);
    }

    /*
    |--------------------------------------------------------------------------
    | Seat Lock
    |--------------------------------------------------------------------------
    */

    public function lockSeat(int $showSeatId, int $userId): ShowSeat
    {
        return DB::transaction(function () use ($showSeatId, $userId) {


            $seat = $this->findSeatForUpdate($showSeatId);

            $this->releaseExpiredLock($seat);

            $this->ensureSeatCanBeLocked($seat, $userId);

            $this->showSeatRepository->update($seat, [
                'status'       => ShowSeat::LOCKED,
                'locked_by'    => $userId,
                'locked_until' => now()->addMinutes(
                    config('booking.lock_minutes', self::LOCK_MINUTES)
                ),
            ]);

            return $seat->fresh();
        }, 3);
    }

    public function unlockSeat(int $showSeatId): ShowSeat
    {
        return DB::transaction(function () use ($showSeatId) {

            $seat = $this->findSeatForUpdate($showSeatId);

            $this->showSeatRepository->update($seat, [
                'status'       => ShowSeat::AVAILABLE,
                'locked_by'    => null,
                'locked_until' => null,
            ]);

            return $seat->fresh();
        });
    }

    public function bookSeat(int $showSeatId, int $userId): ShowSeat
    {
        return DB::transaction(function () use ($showSeatId, $userId) {

            $seat = $this->findSeatForUpdate($showSeatId);

            $this->ensureSeatCanBeBooked($seat, $userId);

            $this->showSeatRepository->update($seat, [
                'status'       => ShowSeat::BOOKED,
                'locked_by'    => null,
                'locked_until' => null,
            ]);

            return $seat->fresh();
        });
    }
    public function bookDirectly(int $showSeatId): ShowSeat
    {
        return DB::transaction(function () use ($showSeatId) {

            $seat = $this->findSeatForUpdate($showSeatId);


            if ($seat->status === ShowSeat::BOOKED) {

                throw new ConflictHttpException(
                    'Seat already booked.'
                );
            }


            if ($seat->status === ShowSeat::BLOCKED) {

                throw new ConflictHttpException(
                    'Seat is blocked.'
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
    /*
    |--------------------------------------------------------------------------
    | Validation Helpers
    |--------------------------------------------------------------------------
    */

    private function findSeatOrFail(int $id): ShowSeat
    {
        $seat = $this->showSeatRepository->findById($id);

        if (! $seat) {
            throw new ModelNotFoundException('Show seat not found.');
        }

        return $seat;
    }

    private function findSeatForUpdate(int $id): ShowSeat
    {
        $seat = $this->showSeatRepository->findForUpdate($id);

        if (! $seat) {
            throw new ModelNotFoundException('Seat not found.');
        }

        return $seat;
    }

    private function ensureSeatCanBeLocked(
        ShowSeat $seat,
        int $userId
    ): void {
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
    }

    private function ensureSeatCanBeBooked(
        ShowSeat $seat,
        int $userId
    ): void {

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
    }

    private function releaseExpiredLock(ShowSeat $seat): void
    {
        if (
            $seat->status === ShowSeat::LOCKED &&
            $seat->locked_until &&
            Carbon::parse($seat->locked_until)->isPast()
        ) {
            $this->showSeatRepository->update($seat, [
                'status'       => ShowSeat::AVAILABLE,
                'locked_by'    => null,
                'locked_until' => null,
            ]);

            $seat->refresh();
        }
    }
}
