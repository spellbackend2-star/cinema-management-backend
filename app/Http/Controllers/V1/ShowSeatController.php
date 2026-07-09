<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowSeat\StoreShowSeatRequest;
use App\Http\Requests\ShowSeat\UpdateShowSeatRequest;
use App\Http\Resources\ShowSeatResource;
use App\Services\ShowSeatService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ShowSeatController extends Controller
{
    use ResponseTrait;

    protected ShowSeatService $showSeatService;

    public function __construct(ShowSeatService $showSeatService)
    {
        $this->showSeatService = $showSeatService;
    }

    /**
     * Display a listing.
     */
    public function index(Request $request)
    {
        $showSeats = $this->showSeatService->index($request->all());

        return $this->successResponse(
            ShowSeatResource::collection($showSeats),
            'Show seats retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(StoreShowSeatRequest $request)
    {
        $showSeat = $this->showSeatService->store($request->validated());

        return $this->successResponse(
            new ShowSeatResource($showSeat),
            'Show seat created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $showSeat = $this->showSeatService->show($id);

        return $this->successResponse(
            new ShowSeatResource($showSeat),
            'Show seat retrieved successfully.'
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateShowSeatRequest $request, int $id)
    {
        $showSeat = $this->showSeatService->update(
            $id,
            $request->validated()
        );

        return $this->successResponse(
            new ShowSeatResource($showSeat),
            'Show seat updated successfully.'
        );
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(int $id)
    {
        $this->showSeatService->destroy($id);

        return $this->successResponse(
            null,
            'Show seat deleted successfully.'
        );
    }
}