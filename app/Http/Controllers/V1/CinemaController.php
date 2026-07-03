<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cinema\CinemaIndexRequest;
use App\Http\Requests\Cinema\CinemaStoreRequest;
use App\Http\Requests\Cinema\CinemaUpdateRequest;
use App\Http\Resources\CinemaResource;
use App\Services\CinemaService;
use App\Traits\ResponseTrait;

class CinemaController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected CinemaService $service
    ) {}

    public function index(CinemaIndexRequest $request)
    {
        return CinemaResource::collection(
            $this->service->index($request->validated())
        );
    }

    public function store(CinemaStoreRequest $request)
    {
        $cinema = $this->service->create(
            $request->validated()
        );

        
        return $this->successResponse(
            new CinemaResource($cinema),
            'Cinema created successfully.',
            201
        );
    }

    public function show(int $id)
    {
        return new CinemaResource(
            $this->service->find($id)
        );
    }

    public function update(CinemaUpdateRequest $request, int $id)
    {
        $cinema = $this->service->update(
            $id,
            $request->validated()
        );

        return $this->successResponse(
            new CinemaResource($cinema),
            'Cinema updated successfully.'
        );
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return $this->successResponse(
            null,
            'Cinema deleted successfully.'
        );
    }
}
