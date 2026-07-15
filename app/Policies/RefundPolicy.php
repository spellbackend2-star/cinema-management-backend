<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\Refund;
use App\Models\User;

class RefundPolicy
{
    /**
     * Anyone who owns the underlying payment can view/request a refund on it.
     */
    public function view(User $user, Refund $refund): bool
    {
        return $user->id === $refund->payment->booking->user_id || $user->isAdmin();
    }

    public function requestRefund(User $user, Payment $payment): bool
    {
        return $user->id === $payment->booking->user_id;
    }

    /**
     * Only staff/admins can process or fail a refund.
     */
    public function process(User $user, Refund $refund): bool
    {
        return $user->can('refund.process');
    }
    
    public function markFailed(User $user, Refund $refund): bool
    {
        return $user->isAdmin();
    }
}
