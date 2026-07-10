<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowSeat\StoreShowSeatRequest;
use App\Http\Requests\ShowSeat\UpdateShowSeatRequest;
use App\Http\Requests\ShowSeat\LockSeatRequest;
use App\Http\Requests\ShowSeat\BookSeatRequest;
use App\Http\Requests\ShowSeat\UnlockSeatRequest;
use App\Http\Resources\ShowSeatResource;
use App\Services\ShowSeatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowSeatController extends Controller
{
    public function __construct(
        protected ShowSeatService $showSeatService
    ) {}

    public function index(Request $request)
    {
        $showSeats = $this->showSeatService->index(
            $request->only('per_page')
        );

        return ShowSeatResource::collection($showSeats);
    }

    public function store(StoreShowSeatRequest $request)
    {
        $showSeat = $this->showSeatService->store(
            $request->validated()
        );

        return new ShowSeatResource($showSeat);
    }

    public function show(int $id)
    {
        return new ShowSeatResource(
            $this->showSeatService->show($id)
        );
    }

    public function update(UpdateShowSeatRequest $request, int $id)
    {
        $showSeat = $this->showSeatService->update(
            $id,
            $request->validated()
        );

        return new ShowSeatResource($showSeat);
    }

    public function destroy(int $id)
    {
        $this->showSeatService->destroy($id);

        return response()->json([
            'message' => 'Show seat deleted successfully.'
        ]);
    }

    public function lock(LockSeatRequest $request, int $id)
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

    public function book(BookSeatRequest $request, int $id)
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

    public function unlock(UnlockSeatRequest $request, int $id)
    {
        $seat = $this->showSeatService->unlockSeat($id);

        return response()->json([
            'message' => 'Seat unlocked successfully.',
            'data' => new ShowSeatResource($seat),
        ]);
    }
}
