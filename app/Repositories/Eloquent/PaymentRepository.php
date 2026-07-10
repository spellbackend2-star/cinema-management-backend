<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Interfaces\PaymentRepositoryInterface;

class PaymentRepository implements PaymentRepositoryInterface
{

    public function getAll(array $filters = [])
    {

        $query = Payment::query()->with([
            'user',
            'booking',
        ]);



        // Payment reference
        $query->when(
            !empty($filters['payment_references']),
            function ($q) use ($filters) {

                $q->where(
                    'payment_references',
                    $filters['payment_references']
                );
            }
        );



        // Payment method
        $query->when(
            !empty($filters['payment_method']),
            function ($q) use ($filters) {

                $q->where(
                    'payment_method',
                    $filters['payment_method']
                );
            }
        );



        // Payment status
        $query->when(
            !empty($filters['payment_status']),
            function ($q) use ($filters) {

                $q->where(
                    'payment_status',
                    $filters['payment_status']
                );
            }
        );



        // User filter
        $query->when(
            !empty($filters['user_id']),
            function ($q) use ($filters) {

                $q->where(
                    'user_id',
                    $filters['user_id']
                );
            }
        );



        // Booking filter
        $query->when(
            !empty($filters['booking_id']),
            function ($q) use ($filters) {

                $q->where(
                    'booking_id',
                    $filters['booking_id']
                );
            }
        );



        // Currency
        $query->when(
            !empty($filters['currency']),
            function ($q) use ($filters) {

                $q->where(
                    'currency',
                    strtoupper($filters['currency'])
                );
            }
        );



        // Date range
        $query->when(
            !empty($filters['date_from']),
            function ($q) use ($filters) {

                $q->whereDate(
                    'payment_date',
                    '>=',
                    $filters['date_from']
                );
            }
        );



        $query->when(
            !empty($filters['date_to']),
            function ($q) use ($filters) {

                $q->whereDate(
                    'payment_date',
                    '<=',
                    $filters['date_to']
                );
            }
        );



        return $query
            ->latest('payment_date')
            ->paginate(
                $filters['per_page'] ?? 10
            );
    }





    public function findById(int $id)
    {
        return Payment::with([
            'user',
            'booking',
        ])
            ->findOrFail($id);
    }





    public function create(array $data)
    {
        return Payment::create($data);
    }





    public function update(int $id, array $data)
    {

        $payment = Payment::findOrFail($id);

        $payment->update($data);


        return $payment->fresh([
            'user',
            'booking',
        ]);
    }





    public function findByReference(string $reference)
    {
        return Payment::where(
             'transaction_id',
            $reference,
        )
            ->firstOrFail();
    }





    public function existsByReference(string $ref): bool
    {
        return Payment::where(
            'transaction_id',
            $ref,
        )
            ->exists();
    }





    public function delete(int $id)
    {
        return Payment::destroy($id);
    }
}
