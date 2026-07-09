<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;


use App\Http\Requests\ShowPrice\StoreShowPriceRequest;
use App\Http\Requests\ShowPrice\UpdateShowPriceRequest;
use App\Http\Resources\ShowPriceResource;
use App\Models\ShowPrice;
use App\Services\ShowPriceService;
use Illuminate\Http\JsonResponse;

class ShowPriceController extends Controller
{
    public function __construct(
        protected ShowPriceService $service
    ) {
    }

    public function index()
    {
        return ShowPriceResource::collection(
            $this->service->index()
        );
    }

    public function store(StoreShowPriceRequest $request): ShowPriceResource
    {
        return new ShowPriceResource(
            $this->service->create($request->validated())
        );
    }

    public function show(ShowPrice $showPrice): ShowPriceResource
    {
        return new ShowPriceResource(
            $this->service->show($showPrice->id)
        );
    }

    public function update(UpdateShowPriceRequest $request, ShowPrice $showPrice): ShowPriceResource
    {
        return new ShowPriceResource(
            $this->service->update($showPrice, $request->validated())
        );
    }

    public function destroy(ShowPrice $showPrice): JsonResponse
    {
        $this->service->delete($showPrice);

        return response()->json([
            'message' => 'Show price deleted successfully.'
        ]);
    }
}