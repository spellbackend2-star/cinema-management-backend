<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seats\IndexSeatRequest;
use App\Http\Requests\Seats\StoreSeatRequest;
use App\Http\Requests\Seats\UpdateSeatRequest;
use App\Http\Resources\SeatResource;
use App\Services\SeatService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected SeatService $service
    ) {}

    public function index(IndexSeatRequest $request)
{
    return SeatResource::collection(
        $this->service->index(
            $request->validated(),
            $request->user()
        )
    );
}

public function store(StoreSeatRequest $request)
{
    $seat = $this->service->store(
        $request->validated(),
        $request->user()
    );

    return $this->successResponse(
        new SeatResource($seat),
        'Seat created successfully.',
        201
    );
}

public function show(Request $request, int $id)
{
    return new SeatResource(
        $this->service->show($id, $request->user())
    );
}

public function update(UpdateSeatRequest $request, int $id)
{
    $seat = $this->service->update(
        $id,
        $request->validated(),
        $request->user()
    );

    return $this->successResponse(
        new SeatResource($seat),
        'Seat updated successfully.'
    );
}

public function destroy(Request $request, int $id)
{
    $this->service->destroy($id, $request->user());

    return $this->successResponse(
        null,
        'Seat deleted successfully.'
    );
}
}