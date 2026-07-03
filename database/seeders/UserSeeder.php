<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Staff::firstOrCreate(
            ['email' => 'admin@cinema.com'],
            [
                'name' => 'Super Admin',
                'employee_code' => 'EMP-0001',
                'password' => Hash::make('Admin@123'),
                'email_verified_at' => now(),
                // 'company_id' => null, // nullable for super_admin? see note below
            ]
        );
        $superAdmin->assignRole('super_admin');

        $companyAdmin = Staff::firstOrCreate(
            ['email' => 'company@cinema.com'],
            [
                'name' => 'Company Admin',
                'employee_code' => 'EMP-0002',
                'password' => Hash::make('Company@123'),
                'email_verified_at' => now(),
                'company_id' => 1, // oversees this company
                'cinema_id' => null, // not tied to one specific cinema
            ]
        );
        $companyAdmin->assignRole('company_admin');

        $branchManager = Staff::firstOrCreate(
            ['email' => 'manager@cinema.com'],
            [
                'name' => 'Branch Manager',
                'employee_code' => 'EMP-0003',
                'password' => Hash::make('Manager@123'),
                'email_verified_at' => now(),
                'company_id' => 1,
                'cinema_id' => 1,
            ]
        );
        $branchManager->assignRole('branch_manager');

        $ticketCounter = Staff::firstOrCreate(
            ['email' => 'counter@cinema.com'],
            [
                'name' => 'Ticket Counter',
                'employee_code' => 'EMP-0004',
                'password' => Hash::make('Counter@123'),
                'email_verified_at' => now(),
                 'company_id' => 1,
                'cinema_id' => 1,
            ]
        );
        $ticketCounter->assignRole('ticket_counter');

        $cashier = Staff::firstOrCreate(
            ['email' => 'cashier@cinema.com'],
            [
                'name' => 'Cashier',
                'employee_code' => 'EMP-0005',
                'password' => Hash::make('Cashier@123'),
                'email_verified_at' => now(),
                 'company_id' => 1,
                'cinema_id' => 1,
            ]
        );
        $cashier->assignRole('cashier');

        // Customer stays on User (api guard)
        $customer = User::firstOrCreate(
            ['email' => 'customer@cinema.com'],
            [
                'name' => 'Customer',
                'password' => Hash::make('Customer@123'),
                'email_verified_at' => now(),
            ]
        );
        $customer->assignRole('customer');
    }
}