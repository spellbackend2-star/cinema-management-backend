<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoyaltyAccount\RedeemPointsRequest;
use App\Services\LoyaltyAccountService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;

class LoyaltyController extends Controller
{
    use ResponseTrait;
    use AuthorizesWithPermission;
    public function __construct(
        protected LoyaltyAccountService $service
    ) {}

    /**
     * Get loyalty account details
     */
    public function account()
    {
        $this->authorizePermission('loyalty.view');
        return $this->successResponse(
            $this->service->account(auth()->id()),
            'Loyalty account fetched successfully.'
        );
    }

    /**
     * Redeem loyalty points
     */
    public function redeem(RedeemPointsRequest $request)
    {
        $this->authorizePermission('loyalty.create');
        return $this->successResponse(
            $this->service->redeem(
                auth()->id(),
                $request->validated()['points']
            ),
            'Points redeemed successfully.'
        );
    }

    /**
     * Loyalty transaction history
     */
    public function history()
    {
        $this->authorizePermission('loyalty.view');
        return $this->successResponse(
            $this->service->history(auth()->id()),
            'Loyalty history fetched successfully.'
        );
    }
}