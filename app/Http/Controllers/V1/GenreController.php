<?php

namespace App\Http\Controllers\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\Genre\StoreGenreRequest;
use App\Http\Requests\Genre\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use App\Services\GenreService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;

class GenreController extends Controller
{
    use ResponseTrait;
    use AuthorizesWithPermission;
    public function __construct(
        protected GenreService $genreService
    ) {}

    public function index()
    {
        $this->authorizePermission('genre.view');
        $genres = $this->genreService->index();

        return GenreResource::collection($genres);
    }

    public function store(StoreGenreRequest $request)
    {

     $this->authorizePermission('genre.create');
        $genre = $this->genreService->store($request->validated());

        return  $this->successResponse(
            new GenreResource($genre),
            'Genre created successfully.',
         201);
    }

    public function show(Genre $genre)
    {
         $this->authorizePermission('genre.view');
        return new GenreResource($genre);
    }

    public function update(UpdateGenreRequest $request, Genre $genre)
    {
         $this->authorizePermission('genre.update');
        $genre = $this->genreService->update($genre, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Genre updated successfully.',
            'data' => new GenreResource($genre),
        ]);
    }

    public function destroy(Genre $genre)
    {
         $this->authorizePermission('genre.delete');
        $this->genreService->destroy($genre);

        return response()->json([
            'success' => true,
            'message' => 'Genre deleted successfully.',
        ]);
    }
}