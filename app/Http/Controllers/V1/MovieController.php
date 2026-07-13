<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\IndexMovieRequest;
use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Services\MovieService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;

class MovieController extends Controller
{
    use ResponseTrait;
    use AuthorizesWithPermission;

    public function __construct(
        protected MovieService $service
    ) {}



    public function index(IndexMovieRequest $request)
    {
        $this->authorizePermission('movie.view');
        $movies = $this->service->index(
            $request->validated()
        );


        return $this->successResponse(
            MovieResource::collection($movies),
            'Movies fetched successfully'
        );
    }

    public function store(StoreMovieRequest $request)
    {
        $this->authorizePermission('movie.create');
        $movie = $this->service->store(
            $request->validated()
        );


        return $this->successResponse(
            new MovieResource($movie),
            'Movie created successfully',
            201
        );
    }

    public function show(int $id)
    {
        $this->authorizePermission('movie.view');
        $movie = $this->service->show($id);


        return $this->successResponse(
            new MovieResource($movie),
            'Movie fetched successfully'
        );
    }

    public function update(UpdateMovieRequest $request, int $id)
    {
        $this->authorizePermission('movie.update');
        $movie = $this->service->update(
            $id,
            $request->validated()
        );


        return $this->successResponse(
            new MovieResource($movie),
            'Movie updated successfully'
        );
    }

    public function destroy(int $id)
    {

        $this->authorizePermission('movie.delete');
        $this->service->destroy($id);


        return $this->successResponse(
            null,
            'Movie deleted successfully'
        );
    }
}
