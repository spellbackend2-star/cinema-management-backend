<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Staff Guard
        |--------------------------------------------------------------------------
        */

        $staffGuard = 'staff';

        $staffRoles = [

            'super_admin' => Permission::where('guard_name', $staffGuard)
                ->pluck('name')
                ->toArray(),

            'company_admin' => [
                'company.view',
                'company.create',
                'company.update',
                'company.delete',

                'cinema.view',
                'cinema.create',
                'cinema.update',
                'cinema.delete',

                'screen.view',
                'screen.create',
                'screen.update',
                'screen.delete',

                'seat_category.view',
                'seat_category.create',
                'seat_category.update',
                'seat_category.delete',

                'seat.view',
                'seat.create',
                'seat.update',
                'seat.delete',

                'movie.view',
                'movie.create',
                'movie.update',
                'movie.delete',

                'genre.view',
                'genre.create',
                'genre.update',
                'genre.delete',

                'language.view',
                'language.create',
                'language.update',
                'language.delete',

                'cast.view',
                'cast.create',
                'cast.update',
                'cast.delete',

                'director.view',
                'director.create',
                'director.update',
                'director.delete',

                'show.view',
                'show.create',
                'show.update',
                'show.delete',

                'booking.view',

                'ticket.view',

                'payment.view',

                'food.view',
                'food.create',
                'food.update',
                'food.delete',

                'food_order.view',

                'coupon.view',
                'coupon.create',
                'coupon.update',
                'coupon.delete',

                'offer.view',
                'offer.create',
                'offer.update',
                'offer.delete',

                'report.view',
                'report.sales',
                'report.booking',
                'report.revenue',

                'dashboard.view',
            ],

            'branch_manager' => [
                'screen.view',
                'seat.view',
                'movie.view',
                'show.view',
                'show.create',
                'show.update',
                'booking.view',
                'ticket.view',
                'ticket.print',
                'dashboard.view',
            ],

            'ticket_counter' => [
                'movie.view',
                'show.view',
                'booking.view',
                'booking.create',
                'booking.cancel',
                'booking.checkout',
                'seat.view',
                'seat.lock',
                'ticket.view',
                'ticket.print',
            ],

            'cashier' => [
                'booking.view',
                'payment.view',
                'payment.process',
                'payment.refund',
                'ticket.view',
                'ticket.print',
            ],
        ];

        foreach ($staffRoles as $role => $permissions) {
            Role::findByName($role, $staffGuard)
                ->syncPermissions($permissions);
        }

        /*
        |--------------------------------------------------------------------------
        | Customer Guard
        |--------------------------------------------------------------------------
        */

        $apiGuard = 'api';

        Role::findByName('customer', $apiGuard)
            ->syncPermissions([
                'movie.view',
                'show.view',

                'booking.view',
                'booking.create',
                'booking.cancel',

                'payment.process',

                'ticket.view',
                'ticket.download',

                'profile.view',
                'profile.update',
            ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}