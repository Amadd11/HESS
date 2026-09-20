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
        // Pastikan role super-admin tersedia
        $adminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);

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
                    'password' => Hash::make('password'),
                ]
            );
        }

        if (! $admin->hasRole('super-admin')) {
            $admin->assignRole($adminRole);
        }
    }
}
