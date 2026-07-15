<?php

namespace App\Services;

use App\Models\Refund;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\RefundRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RefundService
{
    public function __construct(
        protected RefundRepositoryInterface $repository,
        protected PaymentRepositoryInterface $paymentRepository
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }

    public function getById(int $id): Refund
    {
        $refund = $this->repository->findById($id);

        if (!$refund) {
            throw new NotFoundHttpException('Refund not found.');
        }

        return $refund;
    }

    public function store(array $data): Refund
    {
        return DB::transaction(function () use ($data) {

            $payment = $this->paymentRepository->findById($data['payment_id']);

            if (!$payment) {
                throw new NotFoundHttpException('Payment not found.');
            }

            if ($payment->status !== 'SUCCESS') {
                throw new ConflictHttpException(
                    'Refund can only be created for successful payments.'
                );
            }

            $totalRefunded = $payment->refunds()
                ->where('status', 'PROCESSED')
                ->sum('amount');

            if (($totalRefunded + $data['amount']) > $payment->amount) {
                throw new ConflictHttpException(
                    'Refund amount exceeds paid amount.'
                );
            }

            return $this->repository->create([
                'payment_id' => $payment->id,
                'amount' => $data['amount'],
                'reason' => $data['reason'] ?? null,
                'status' => 'PENDING',
            ]);
        });
    }

    public function process(int $id, array $data): Refund
    {
        return DB::transaction(function () use ($id, $data) {

            $refund = $this->getById($id);

            if ($refund->status !== 'PENDING') {
                throw new ConflictHttpException(
                    'Refund has already been processed.'
                );
            }

            // TODO:
            // Call Khalti/eSewa refund API here.

            $refund = $this->repository->update($refund, [
                'status' => 'PROCESSED',
                'system_response' => $data['admin_response'] ?? null,
                'processed_by_user_id' => Auth::id(),
                'processed_at' => now(),
            ]);

            $payment = $refund->payment;

            $processedAmount = $payment->refunds()
                ->where('status', 'PROCESSED')
                ->sum('amount');

            if ($processedAmount >= $payment->amount) {
                $payment->update([
                    'status' => 'REFUNDED',
                ]);
            }

            return $refund;
        });
    }

    public function delete(int $id): bool
    {
        $refund = $this->getById($id);

        if ($refund->status === 'PROCESSED') {
            throw new ConflictHttpException(
                'Processed refunds cannot be deleted.'
            );
        }

        return $this->repository->delete($refund);
    }
}
