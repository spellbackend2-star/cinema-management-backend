<?php

namespace App\Repositories\Eloquent;

use App\Models\Refund;
use App\Repositories\Interfaces\RefundRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RefundRepository implements RefundRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return Refund::with([
            'payment.booking',
            'processedBy',
        ])
        ->latest()
        ->paginate($filters['per_page'] ?? 10);
    }

    public function findById(int $id): ?Refund
    {
        return Refund::with([
            'payment.booking',
            'payment.booking.user',
            'processedBy',
        ])->find($id);
    }

    public function create(array $data): Refund
    {
        return Refund::create($data);
    }

    public function update(Refund $refund, array $data): Refund
    {
        $refund->update($data);

        return $refund->fresh([
            'payment.booking',
            'processedBy',
        ]);
    }

    public function delete(Refund $refund): bool
    {
        return $refund->delete();
    }
}