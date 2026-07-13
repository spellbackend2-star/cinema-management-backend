<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffIndexRequest;
use App\Http\Requests\Staff\StaffStoreRequest;
use App\Http\Requests\Staff\StaffUpdateRequest;
use App\Http\Resources\StaffResource;
use App\Models\User;
use App\Services\StaffService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;

class StaffController extends Controller
{
    use ResponseTrait;
    use AuthorizesWithPermission;
    protected StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    public function index(StaffIndexRequest $request)
    {
        $this->authorizePermission('staff.view');
        $filters = $request->validated();
        $user = $request->user();

        if ($user->hasRole('company_admin')) {
            $filters['company_id'] = $user->company_id;
        }

        if ($user->hasRole('cinema_admin')) {
            $filters['company_id'] = $user->company_id;
            $filters['cinema_id'] = $user->cinema_id;
        }

        if ($user->hasRole('staff')) {
            $filters['company_id'] = $user->company_id;
            $filters['cinema_id'] = $user->cinema_id;
        }

        $staff = $this->staffService->index($filters);

        return $this->successResponse(
            StaffResource::collection($staff),
            'Staff list fetched successfully'
        );
    }

    public function store(StaffStoreRequest $request)
    {
        $this->authorizePermission('staff.create');
        $staff = $this->staffService->store($request->validated(), $request->user());

        return $this->successResponse(
            new StaffResource($staff),
            'Staff created successfully'
        );
    }

    public function show(User $staff)
    {
        $this->authorizePermission('staff.view');
        $staff = $this->staffService->show($staff->id);

        return $this->successResponse(
            new StaffResource($staff),
            'Staff details fetched successfully'
        );
    }

    public function update(StaffUpdateRequest $request, User $staff)
    {
        $this->authorizePermission('staff.update');
        $updated = $this->staffService->update($staff->id, $request->validated());

        return $this->successResponse(
            new StaffResource($updated),
            'Staff updated successfully'
        );
    }

    public function destroy(User $staff)
    {
        $this->authorizePermission('staff.delete');
        $this->staffService->destroy($staff->id);

        return $this->successResponse(null, 'Staff deleted successfully');
    }
}
