<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\People\StorePeopleRequest;
use App\Http\Requests\People\UpdatePeopleRequest;
use App\Http\Resources\PeopleResource;
use App\Models\People;
use App\Models\Person;
use App\Services\PeopleService;
use App\Traits\ResponseTrait;

class PeopleController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected PeopleService $peopleService
    ) {}

    public function index()
    {
        $people = $this->peopleService->index();

        return $this->successResponse(
            PeopleResource::collection($people),
            'People retrieved successfully.'
        );
    }

    public function store(StorePeopleRequest $request)
    {
        $person = $this->peopleService->store($request->validated());

        return $this->successResponse(
            new PeopleResource($person),
            'Person created successfully.',
            201
        );
    }

    public function show(Person $person)
    {
        return $this->successResponse(
            new PeopleResource($person),
            'Person retrieved successfully.'
        );
    }

    public function update(UpdatePeopleRequest $request, Person $person)
    {
        $person = $this->peopleService->update($person, $request->validated());

        return $this->successResponse(
            new PeopleResource($person),
            'Person updated successfully.'
        );
    }

    public function destroy(Person $person)
    {
        $this->peopleService->destroy($person);

        return $this->successResponse(
            null,
            'Person deleted successfully.'
        );
    }
}