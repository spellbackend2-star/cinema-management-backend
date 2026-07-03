<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = ['api', 'staff'];

        $modules = [
            'user',
            'role',
            'permission',

            'company',
            'cinema',
            'screen',

            'seat_category',
            'seat',

            'movie',
            'genre',
            'language',
            'cast',
            'director',

            'show',

            'booking',
            'ticket',
            'payment',

            'food',
            'food_order',

            'coupon',
            'offer',

            'report',
            'dashboard',
            'profile',
        ];

        $actions = [
            'view',
            'create',
            'update',
            'delete',
        ];

        foreach (['staff','api'] as $guard) {

            foreach ($modules as $module) {

                foreach ($actions as $action) {

                    Permission::firstOrCreate([
                        'name' => "{$module}.{$action}",
                        'guard_name' => $guard,
                    ]);
                }
            }

            $extraPermissions = [

                'booking.checkout',
                'booking.confirm',
                'booking.cancel',

                'seat.lock',
                'seat.unlock',

                'ticket.print',
                'ticket.download',
                'ticket.scan',

                'payment.process',
                'payment.refund',

                'report.sales',
                'report.booking',
                'report.revenue',

                'role.assign',
                'permission.assign',
            ];

            foreach ($extraPermissions as $permission) {

                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => $guard,
                ]);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}