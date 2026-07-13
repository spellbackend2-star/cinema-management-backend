<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

trait AuthorizesWithPermission
{
    protected function authorizePermission(string $permission): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!Permission::where('name', $permission)->exists()) {
            abort(500, "Permission '{$permission}' does not exist.");
        }

        if (!$user || !$user->can($permission)) {
            abort(403, 'You do not have permission to perform this action.');
        }
    }
}