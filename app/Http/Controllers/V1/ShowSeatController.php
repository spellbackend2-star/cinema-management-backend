<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowSeat\StoreShowSeatRequest;
use App\Http\Requests\ShowSeat\UpdateShowSeatRequest;
use App\Http\Resources\ShowSeatResource;
use App\Services\ShowSeatService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowSeatController extends Controller
{
     use ResponseTrait, AuthorizesWithPermission;

    public function __construct(
        protected ShowSeatService $showSeatService
    ) {}

    public function index(Request $request)
    {
         $this->authorizePermission('show-seat.view');
        $showSeats = $this->showSeatService->index(
            $request->only('per_page')
        );

        return $this->successResponse(
            ShowSeatResource::collection($showSeats),
            'Show seats retrieved successfully.'
        );
    }

    public function store(StoreShowSeatRequest $request)
    {
         $this->authorizePermission('show-seat.create');
        $showSeat = $this->showSeatService->store(
            $request->validated()
        );

        return $this->successResponse(
            new ShowSeatResource($showSeat),
            'Show seat created successfully.',
            201
        );
    }

    public function show(int $id)
    {
         $this->authorizePermission('show-seat.view');
        return $this->successResponse(
            new ShowSeatResource($id),
            'Show SEAT retrieved successfully.'
        );
    }

    public function update(UpdateShowSeatRequest $request, int $id)
    {
         $this->authorizePermission('show-seat.update');
        $showSeat = $this->showSeatService->update(
            $id,
            $request->validated()
        );

        return $this->successResponse(
            new ShowSeatResource($showSeat),
            'Show seat updated successfully.'
        );
    }

    public function destroy(int $id)
    {
         $this->authorizePermission('show-seat.delete');
        $this->showSeatService->destroy($id);

       return $this->successResponse(
            null,
            'Show schedule deleted successfully.'
        );
    }

    public function lock(int $id)
    {
        $seat = $this->showSeatService->lockSeat(
            $id,
            Auth::id()
        );

        return response()->json([
            'message' => 'Seat locked successfully.',
            'data' => new ShowSeatResource($seat),
        ]);
    }

    public function book( int $id)
    {
        $seat = $this->showSeatService->bookSeat(
            $id,
            Auth::id()
        );

        return response()->json([
            'message' => 'Seat booked successfully.',
            'data' => new ShowSeatResource($seat),
        ]);
    }

    public function unlock( int $id)
    {
        $seat = $this->showSeatService->unlockSeat($id);

        return response()->json([
            'message' => 'Seat unlocked successfully.',
            'data' => new ShowSeatResource($seat),
        ]);
    }
}
