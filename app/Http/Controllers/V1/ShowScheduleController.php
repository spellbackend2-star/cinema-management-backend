<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowSchedule\StoreShowScheduleRequest;
use App\Http\Requests\ShowSchedule\UpdateShowScheduleRequest;
use App\Http\Resources\ShowScheduleResource;
use App\Services\ShowScheduleService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ShowScheduleController extends Controller
{
    use ResponseTrait;

    protected ShowScheduleService $showScheduleService;

    public function __construct(ShowScheduleService $showScheduleService)
    {
        $this->showScheduleService = $showScheduleService;
    }

    /**
     * Display a listing.
     */
    public function index(Request $request)
    {
        $showSchedules = $this->showScheduleService->index($request->all());

        return $this->successResponse(
            ShowScheduleResource::collection($showSchedules),
            'Show schedules retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(StoreShowScheduleRequest $request)
    {
        $showSchedule = $this->showScheduleService->store($request->validated());

        return $this->successResponse(
            new ShowScheduleResource($showSchedule),
            'Show schedule created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $showSchedule = $this->showScheduleService->show($id);

        return $this->successResponse(
            new ShowScheduleResource($showSchedule),
            'Show schedule retrieved successfully.'
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateShowScheduleRequest $request, int $id)
    {
        $showSchedule = $this->showScheduleService->update(
            $id,
            $request->validated()
        );

        return $this->successResponse(
            new ShowScheduleResource($showSchedule),
            'Show schedule updated successfully.'
        );
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(int $id)
    {
        $this->showScheduleService->destroy($id);

        return $this->successResponse(
            null,
            'Show schedule deleted successfully.'
        );
    }
}