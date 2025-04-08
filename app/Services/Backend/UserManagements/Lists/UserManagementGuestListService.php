<?php

declare(strict_types=1);

namespace App\Services\Backend\UserManagements\Lists;

use App\Enums\Roles\RoleEnum;
use App\Models\User;
use Illuminate\Support\Collection;

class UserManagementGuestListService
{
    /**
     * @param int $id
     *
     * @return Collection
     */
    public function getByGuestUser(int $id): Collection
    {
        return User::query()->with('roles')->role(RoleEnum::GUEST)->where('id', $id)->get();
    }
}
