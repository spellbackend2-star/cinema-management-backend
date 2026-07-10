<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Show\StoreShowRequest;
use App\Http\Requests\Show\UpdateShowRequest;
use App\Http\Resources\ShowResource;
use App\Services\ShowSeatService;
use App\Services\ShowService;
use Illuminate\Http\Request;

class ShowController extends Controller
{

    public function __construct(
        protected ShowSeatService $showService
    ) {}



    public function index(Request $request)
    {
        $shows = $this->showService->index(
            $request->all()
        );

        return ShowResource::collection($shows);
    }



    public function store(StoreShowRequest $request)
    {
        $show = $this->showService->store(
            $request->validated()
        );

        return new ShowResource($show);
    }



    public function show(int $id)
    {
        $show = $this->showService->show($id);

        return new ShowResource($show);
    }



    public function update(UpdateShowRequest $request, int $id)
    {
        $show = $this->showService->update(
            $id,
            $request->validated()
        );

        return new ShowResource($show);
    }



    public function destroy(int $id)
    {
        $this->showService->destroy($id);

        return response()->json([
            'message' => 'Show deleted successfully'
        ]);
    }

}