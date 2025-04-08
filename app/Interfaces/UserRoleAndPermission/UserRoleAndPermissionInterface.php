<?php

declare(strict_types=1);

namespace App\Interfaces\UserRoleAndPermission;

use App\Models\User;

interface UserRoleAndPermissionInterface
{
    /**
     * @param User $user
     * @param bool $admin
     * @param array|null $permissions
     *
     * @return bool
     */
    public function createAssignRolesAndPermissions(User $user, bool $admin, ?array $permissions): bool;


    /**
     * @param User $user
     * @param bool $admin
     * @param array|null $permissions
     *
     * @return bool
     */
    public function updateAssignRolesAndPermissions(User $user, bool $admin, ?array $permissions): bool;


    /**
     * @param User $user
     *
     * @return bool
     */
    public function addBannedRole(User $user): bool;


    /**
     * @param User $user
     *
     * @return bool
     */
    public function removeBannedRole(User $user): bool;
}
