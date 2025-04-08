<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Permissions\PermissionEnum;
use App\Enums\Roles\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        // Super Admin
        $superAdmin = Role::query()->updateOrCreate(
            ['name' => RoleEnum::SUPER_ADMIN]
        );
        $superUser = User::query()->updateOrCreate(
            attributes: ['email' => 'superadmin@localkod.com'],
            values: [
                'name' => 'Super Admin',
                'phone' => '05555555555',
                'password' => Hash::make(passwordGeneration(password: '12345678')),
                'created_by' => 1,
            ]
        );
        $superUser->assignRole(roles: $superAdmin);
        $superUser->givePermissionTo(PermissionEnum::values());

        // Admin
        $admin = Role::query()->updateOrCreate(
            ['name' => RoleEnum::ADMIN]
        );
        $adminUser = User::query()->updateOrCreate(
            attributes: ['email' => 'manager@localkod.com'],
            values: [
                'name' => 'Yönetici',
                'phone' => '05555555555',
                'password' => Hash::make(passwordGeneration(password: '12345678')),
                'created_by' => $superUser?->id,
            ]
        );
        $adminUser->assignRole(roles: $admin);
        $adminUser->givePermissionTo(PermissionEnum::values());

        // Guest ( Kısıtlı Yetkilere Sahip Yönetici )
        $guest = Role::query()->updateOrCreate(
            ['name' => RoleEnum::GUEST]
        );
        $guestUser = User::query()->updateOrCreate(
            attributes: ['email' => 'demo@localkod.com'],
            values: [
                'name' => 'Demo Admin',
                'phone' => '05555555555',
                'password' => Hash::make(passwordGeneration(password: '12345678')),
                'created_by' => $superUser?->id,
            ]
        );
        $guestUser->assignRole(roles: $guest);
        $guestUser->givePermissionTo(PermissionEnum::values());
    }
}
