<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeatCategory\IndexSeatCategoryRequest;
use App\Http\Requests\SeatCategory\StoreSeatCategoryRequest;
use App\Http\Requests\SeatCategory\UpdateSeatCategoryRequest;
use App\Http\Resources\SeatCategoryResource;
use App\Services\SeatCategoryService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class SeatCategoryController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected SeatCategoryService $service
    ) {}

    public function index(IndexSeatCategoryRequest $request)
    {
        return SeatCategoryResource::collection(
            $this->service->index($request->validated(), $request->user())
        );
    }

    public function store(StoreSeatCategoryRequest $request)
    {
        $seatCategory = $this->service->store(
            $request->validated(),
            $request->user()
        );

        return $this->successResponse(
            new SeatCategoryResource($seatCategory),
            'Seat category created successfully.',
            201
        );
    }

    public function show(string $id, Request $request)
    {
        return new SeatCategoryResource(
            $this->service->show($id, $request->user())
        );
    }

    public function update(UpdateSeatCategoryRequest $request, string $id)
    {
        $seatCategory = $this->service->update(
            $id,
            $request->validated(),
            $request->user()
        );

        return $this->successResponse(
            new SeatCategoryResource($seatCategory),
            'Seat category updated successfully.'
        );
    }

    public function destroy(string $id, Request $request)
    {
        $this->service->destroy($id, $request->user());

        return $this->successResponse(
            null,
            'Seat category deleted successfully.'
        );
    }
}