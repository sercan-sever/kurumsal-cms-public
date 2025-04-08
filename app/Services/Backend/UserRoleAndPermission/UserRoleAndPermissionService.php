<?php

declare(strict_types=1);

namespace App\Services\Backend\UserRoleAndPermission;

use App\Enums\Permissions\PermissionEnum;
use App\Enums\Roles\RoleEnum;
use App\Interfaces\UserRoleAndPermission\UserRoleAndPermissionInterface;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserRoleAndPermissionService implements UserRoleAndPermissionInterface
{
    /**
     * @param User $user
     * @param bool $admin
     * @param array|null $permissions
     *
     * @return bool
     */
    public function createAssignRolesAndPermissions(User $user, bool $admin, ?array $permissions): bool
    {
        try {
            if ($admin) {
                $user->assignRole(RoleEnum::ADMIN->value);
                $user->givePermissionTo(PermissionEnum::values());
                return true;
            }

            $user->assignRole(RoleEnum::GUEST->value);
            if (!empty($permissions)) {
                $user->givePermissionTo($permissions);

                return true;
            }

            return false;
        } catch (\Exception $exception) {

            Log::error(message: 'UserRoleAndPermissionService (createAssignRolesAndPermissions) : ', context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param User $user
     * @param bool $admin
     * @param array|null $permissions
     *
     * @return bool
     */
    public function updateAssignRolesAndPermissions(User $user, bool $admin, ?array $permissions): bool
    {
        try {
            $user->syncRoles([]); // Tüm roller kaldırılır
            $user->syncPermissions([]); // Tüm izinler kaldırılır

            if ($admin) {
                $user->assignRole(RoleEnum::ADMIN->value);
                $user->givePermissionTo(PermissionEnum::values());

                return true;
            }

            $user->assignRole(RoleEnum::GUEST->value);
            if (!empty($permissions)) {
                $user->givePermissionTo($permissions);

                return true;
            }

            return false;
        } catch (\Exception $exception) {
            Log::error(message: 'UserRoleAndPermissionService (updateAssignRolesAndPermissions) : ', context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param User $user
     *
     * @return bool
     */
    public function addBannedRole(User $user): bool
    {
        try {
            $user->assignRole(RoleEnum::BANNED->value);

            if ($user->hasRole(RoleEnum::BANNED->value)) {
                return true;
            }

            return false;
        } catch (\Exception $exception) {

            Log::error(message: 'UserRoleAndPermissionService (addBannedRole) : ', context: [$exception->getMessage()]);
            return false;
        }
    }


    /**
     * @param User $user
     *
     * @return bool
     */
    public function removeBannedRole(User $user): bool
    {
        try {
            if ($user->hasRole(RoleEnum::BANNED->value)) {
                $user->removeRole(RoleEnum::BANNED->value);
            }

            if (!$user->hasRole(RoleEnum::BANNED->value)) {
                return true;
            }

            return false;
        } catch (\Exception $exception) {
            // Hata kaydı
            Log::error('UserRoleAndPermissionService (removeBannedRole): ' . $exception->getMessage());
            return false;
        }
    }
}
