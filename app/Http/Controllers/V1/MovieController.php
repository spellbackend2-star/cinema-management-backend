<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\IndexMovieRequest;
use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Services\MovieService;
use App\Traits\ResponseTrait;

class MovieController extends Controller
{
    use ResponseTrait;


    public function __construct(
        protected MovieService $service
    ) {}



    public function index(IndexMovieRequest $request)
    {
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
        $movie = $this->service->show($id);


        return $this->successResponse(
            new MovieResource($movie),
            'Movie fetched successfully'
        );
    }



    public function update(UpdateMovieRequest $request, int $id)
    {
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
        $this->service->destroy($id);


        return $this->successResponse(
            null,
            'Movie deleted successfully'
        );
    }
}