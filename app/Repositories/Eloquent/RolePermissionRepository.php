<?php
namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\RolePermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionRepository implements RolePermissionRepositoryInterface
{
    public function assignPermissionsToRole(int $roleId, array $permissions)
    {
        $role = Role::findOrFail($roleId);

        // Sync permissions
        $role->syncPermissions($permissions);

        return $role->load('permissions');
    }

    public function getRoleWithPermissions(int $roleId)
    {
        return Role::with('permissions')->findOrFail($roleId);
    }

    public function getAllRolesWithPermissions()
    {
        return Role::with('permissions')->get();
    }

    public function getAllPermissions()
    {
        return Permission::all();
    }
}