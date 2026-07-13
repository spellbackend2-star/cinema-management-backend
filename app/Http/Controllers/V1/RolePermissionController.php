<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignPermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RolePermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\User;
use App\Services\RolePermissionService;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;

class RolePermissionController extends Controller
{
    use ResponseTrait;

    protected RolePermissionService $service;

    public function __construct(RolePermissionService $service)
    {
        $this->service = $service;
    }

    public function assign(AssignPermissionRequest $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        abort_if(!$user || !$user->can('permission.assign'), 403);

        $role = $this->service->assignPermissions($request->validated());

        return $this->successResponse(
            new RolePermissionResource($role),
            'Permissions assigned successfully'
        );
    }

    public function show($id)
    {
        /** @var User|null $user */
        $user = Auth::user();

        abort_if(!$user || !$user->can('permission.view'), 403);

        $role = $this->service->getRolePermissions($id);

        return $this->successResponse(
            new RolePermissionResource($role),
            'Permission With Role Shows'
        );
    }

    public function indexRoles()
    {
        /** @var User|null $user */
        $user = Auth::user();

        abort_if(!$user || !$user->can('permission.view'), 403);

        $roles = $this->service->getAllRoles();

        return $this->successResponse(
            RoleResource::collection($roles),
            'All roles with permissions'
        );
    }

    public function indexPermissions()
    {
        /** @var User|null $user */
        $user = Auth::user();

        abort_if(!$user || !$user->can('permission.view'), 403);

        $permissions = $this->service->getAllPermissions();

        return $this->successResponse(
            PermissionResource::collection($permissions),
            'All permissions fetched'
        );
    }
}