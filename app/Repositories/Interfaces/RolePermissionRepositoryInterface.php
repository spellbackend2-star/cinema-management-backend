<?php

namespace App\Repositories\Interfaces;

interface RolePermissionRepositoryInterface
{
    public function assignPermissionsToRole(int $roleId, array $permissions);

    public function getRoleWithPermissions(int $roleId);

    public function getAllRolesWithPermissions();

    public function getAllPermissions();
}