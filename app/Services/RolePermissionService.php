<?php
namespace App\Services;

use App\Repositories\Interfaces\RolePermissionRepositoryInterface;

class RolePermissionService
{
    protected $repo;

    public function __construct(RolePermissionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function assignPermissions($data)
    {
        return $this->repo->assignPermissionsToRole(
            $data['role_id'],
            $data['permissions'] ?? []
        );
    }

    public function getRolePermissions($roleId)
    {
        return $this->repo->getRoleWithPermissions($roleId);
    }

    public function getAllRoles()
    {
        return $this->repo->getAllRolesWithPermissions();
    }

    //  NEW
    public function getAllPermissions()
    {
        return $this->repo->getAllPermissions();
    }
}