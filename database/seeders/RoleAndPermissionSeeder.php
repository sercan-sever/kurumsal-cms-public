<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Permissions\PermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Enums\Roles\RoleEnum;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        // Role Create
        foreach (RoleEnum::values() as $role) {
            Role::query()->updateOrCreate(['name' => $role]);
        }

        // Permissions
        foreach (PermissionEnum::values() as $permission) {
            Permission::query()->updateOrCreate(['name' => $permission]);
        }
    }
}
