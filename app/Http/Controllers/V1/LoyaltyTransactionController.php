<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoyaltyTransactionResource;
use App\Services\LoyaltyTransactionService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;

class LoyaltyTransactionController extends Controller
{
    use ResponseTrait;
    use AuthorizesWithPermission;
    public function __construct(
        protected LoyaltyTransactionService $service
    ) {}

    public function index()
    {
        $this->authorizePermission('loyaltyTransaction.view');
        return $this->successResponse(
            LoyaltyTransactionResource::collection(
                $this->service->index(auth()->id())
            ),
            'Loyalty transactions fetched successfully.'
        );
    }

    public function show(int $id)
    {
          $this->authorizePermission('loyaltyTransaction.view');
        return $this->successResponse(
            new LoyaltyTransactionResource(
                $this->service->show($id)
            ),
            'Loyalty transaction fetched successfully.'
        );
    }
}