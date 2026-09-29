<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role super-admin & admin-rs tersedia
        $adminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $adminRsRole = Role::firstOrCreate(['name' => 'admin-rs', 'guard_name' => 'web']);

        // Akun 1: Super Administrator (Full Control)
        $admin = User::where('email', 'admin@hess.id')->first();
        if ($admin) {
            $admin->update([
                'email' => 'admin@hess.id',
                'name' => 'Administrator HESS',
            ]);
        } else {
            $admin = User::firstOrCreate(
                ['email' => 'admin@hess.id'],
                [
                    'name' => 'Administrator HESS',
                    'password' => Hash::make('@Hess321'),
                ]
            );
        }

        if (! $admin->hasRole('super-admin')) {
            $admin->assignRole($adminRole);
        }

        // Akun 2: Admin RS (Dashboard Analytics & Data Respon)
        $adminRs = User::where('email', 'admin.rs@hess.id')->first();
        if ($adminRs) {
            $adminRs->update([
                'email' => 'admin.rs@hess.id',
                'name' => 'Admin RS',
                'password' => Hash::make('@HessRS321'),
            ]);
        } else {
            $adminRs = User::firstOrCreate(
                ['email' => 'admin.rs@hess.id'],
                [
                    'name' => 'Admin RS',
                    'password' => Hash::make('@HessRS321'),
                ]
            );
        }

        if (! $adminRs->hasRole('admin-rs')) {
            $adminRs->assignRole($adminRsRole);
        }
    }
}
