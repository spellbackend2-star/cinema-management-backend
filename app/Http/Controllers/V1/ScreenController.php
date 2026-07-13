<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Screen\ScreenIndexRequest;
use App\Http\Requests\Screen\ScreenUpdateRequest;
use App\Http\Requests\Screen\ScreenStoreRequest;
use App\Http\Resources\ScreenResource;
use App\Services\ScreenService;
use App\Traits\AuthorizesWithPermission;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;

class ScreenController extends Controller
{
    use ResponseTrait;
    use AuthorizesWithPermission;
    public function __construct(
        protected ScreenService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(ScreenIndexRequest $request)
    {
        $this->authorizePermission('screen.view');
        return ScreenResource::collection(
            $this->service->index($request->validated(), $request->user())
        );
    }

    public function store(ScreenStoreRequest $request)
    {
        $this->authorizePermission('screen.create');
        $screen = $this->service->create($request->validated(), $request->user());
        return $this->successResponse(
                new ScreenResource($screen),
                'Screen created successfully.',
                201
            );
    }

    public function show(Request $request, int $id)
    {
         $this->authorizePermission('screen.view');
        return new ScreenResource(
            $this->service->find($id, $request->user())
        );
    }

    public function update(ScreenUpdateRequest $request, string $id)
    {
         $this->authorizePermission('screen.update');
        $screen = $this->service->update($id, $request->validated(), $request->user());
        return $this->successResponse(new ScreenResource($screen), 'Screen updated successfully.');
    }

    public function destroy(string $id, Request $request)
    {
         $this->authorizePermission('screen.delete');
        $this->service->delete($id, $request->user());
        return $this->successResponse(null, 'Screen deleted successfully.');
    }
}
