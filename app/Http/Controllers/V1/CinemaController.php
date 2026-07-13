<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cinema\CinemaIndexRequest;
use App\Http\Requests\Cinema\CinemaStoreRequest;
use App\Http\Requests\Cinema\CinemaUpdateRequest;
use App\Http\Resources\CinemaResource;
use App\Services\CinemaService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;

class CinemaController extends Controller
{
    use ResponseTrait;
    use AuthorizesWithPermission;
    public function __construct(
        protected CinemaService $service
    ) {}

    public function index(CinemaIndexRequest $request)
    {
        $this->authorizePermission('cinema.view');
        return CinemaResource::collection(
            $this->service->index($request->validated())
        );
    }

    public function store(CinemaStoreRequest $request)
    {
        $this->authorizePermission('cinema.create');
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
        $this->authorizePermission('cinema.view');
        return new CinemaResource(
            $this->service->find($id)
        );
    }

    public function update(CinemaUpdateRequest $request, int $id)
    {
        $this->authorizePermission('cinema.update');
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
        $this->authorizePermission('cinema.delete');
        $this->service->delete($id);

        return $this->successResponse(
            null,
            'Cinema deleted successfully.'
        );
    }
}
