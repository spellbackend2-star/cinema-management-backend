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

        $guard = 'api';

        $allPermissions = [
            'company.view', 'company.create', 'company.update', 'company.delete',
            'cinema.view', 'cinema.create', 'cinema.update', 'cinema.delete',
            'screen.view', 'screen.create', 'screen.update', 'screen.delete',
            'seat_category.view', 'seat_category.create', 'seat_category.update', 'seat_category.delete',
            'seat.view', 'seat.create', 'seat.update', 'seat.delete', 'seat.lock',
            'movie.view', 'movie.create', 'movie.update', 'movie.delete',
            'genre.view', 'genre.create', 'genre.update', 'genre.delete',
            'language.view', 'language.create', 'language.update', 'language.delete',
            'cast.view', 'cast.create', 'cast.update', 'cast.delete',
            'director.view', 'director.create', 'director.update', 'director.delete',
            'show.view', 'show.create', 'show.update', 'show.delete',
            'booking.view', 'booking.create', 'booking.cancel', 'booking.checkout',
            'ticket.view', 'ticket.print', 'ticket.download',
            'payment.view', 'payment.process', 'payment.refund',
            'food.view', 'food.create', 'food.update', 'food.delete',
            'food_order.view',
            'coupon.view', 'coupon.create', 'coupon.update', 'coupon.delete',
            'offer.view', 'offer.create', 'offer.update', 'offer.delete',
            'report.view', 'report.sales', 'report.booking', 'report.revenue',
            'dashboard.view',
            'profile.view', 'profile.update',

            // staff management
            'staff.view', 'staff.create', 'staff.update', 'staff.delete',
        ];

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guard,
            ]);
        }

        $roles = [
            'company_admin' => [
                'company.view', 'company.create', 'company.update', 'company.delete',
                'cinema.view', 'cinema.create', 'cinema.update', 'cinema.delete',
                'screen.view', 'screen.create', 'screen.update', 'screen.delete',
                'seat_category.view', 'seat_category.create', 'seat_category.update', 'seat_category.delete',
                'seat.view', 'seat.create', 'seat.update', 'seat.delete',
                'movie.view', 'movie.create', 'movie.update', 'movie.delete',
                'genre.view', 'genre.create', 'genre.update', 'genre.delete',
                'language.view', 'language.create', 'language.update', 'language.delete',
                'cast.view', 'cast.create', 'cast.update', 'cast.delete',
                'director.view', 'director.create', 'director.update', 'director.delete',
                'show.view', 'show.create', 'show.update', 'show.delete',
                'booking.view',
                'ticket.view',
                'payment.view',
                'food.view', 'food.create', 'food.update', 'food.delete',
                'food_order.view',
                'coupon.view', 'coupon.create', 'coupon.update', 'coupon.delete',
                'offer.view', 'offer.create', 'offer.update', 'offer.delete',
                'report.view', 'report.sales', 'report.booking', 'report.revenue',
                'dashboard.view',
                'staff.view', 'staff.create', 'staff.update', 'staff.delete',
            ],

            'branch_manager' => [
                'screen.view', 'seat.view', 'movie.view',
                'show.view', 'show.create', 'show.update',
                'booking.view', 'ticket.view', 'ticket.print',
                'dashboard.view',
                'staff.view', 'staff.create', // can create ticket_counter/cashier under them
            ],

            'ticket_counter' => [
                'movie.view', 'show.view',
                'booking.view', 'booking.create', 'booking.cancel', 'booking.checkout',
                'seat.view', 'seat.lock',
                'ticket.view', 'ticket.print',
            ],

            'cashier' => [
                'booking.view',
                'payment.view', 'payment.process', 'payment.refund',
                'ticket.view', 'ticket.print',
            ],

            'customer' => [
                'movie.view', 'show.view',
                'booking.view', 'booking.create', 'booking.cancel',
                'payment.process',
                'ticket.view', 'ticket.download',
                'profile.view', 'profile.update',
            ],
        ];

        foreach ($roles as $role => $permissions) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => $guard,
            ])->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}