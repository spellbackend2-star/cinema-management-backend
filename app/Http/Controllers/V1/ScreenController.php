<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Screen\ScreenIndexRequest;
use App\Http\Requests\Screen\ScreenUpdateRequest;
use App\Http\Resources\ScreenResource;
use App\Services\ScreenService;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;

class ScreenController extends Controller
{
  use ResponseTrait;

    public function __construct(
        protected ScreenService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(ScreenIndexRequest $request)
    {
        return ScreenResource::collection(
            $this->service->index($request->validated())
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScreenStoreRequest $request)
    {
     $screen = $this->service->create(
            $request->validated()
        );

        return $this->successResponse(
            new ScreenResource($screen),
            'Screen created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new ScreenResource(
            $this->service->find($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScreenUpdateRequest $request, string $id)
    {
        $screen = $this->service->update(
            $id,
            $request->validated()
        );

        return $this->successResponse(
            new ScreenResource($screen),
            'Screen updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->service->delete($id);

        return $this->successResponse(
            null,
            'Screen deleted successfully.'
        );
    }
}
