<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Customer roles (API guard)
        Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'api',
        ]);

        // Staff roles (Staff guard)
        $staffRoles = [
        
            'company_admin',
            'branch_manager',
            'ticket_counter',
            'cashier',
        ];

       
        foreach ($staffRoles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'api',
            ]);
        }
    }
}