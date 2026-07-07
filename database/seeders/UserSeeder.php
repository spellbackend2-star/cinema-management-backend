<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $companyAdmin = User::firstOrCreate(
            ['email' => 'company@cinema.com'],
            [
                'name' => 'Company Admin',
                'employee_code' => 'EMP-0001',
                'password' => Hash::make('Company@123'),
           
                'email_verified_at' => now(),
            ]
        );

        $companyAdmin->assignRole('company_admin');
    }
}