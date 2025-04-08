<?php

declare(strict_types=1);

namespace App\Services\Backend\UserManagements\Lists;

use App\Enums\Roles\RoleEnum;
use App\Models\User;
use Illuminate\Support\Collection;

class UserManagementAdminListService
{
    /**
     * @return Collection
     */
    public function getActiveUsers(): Collection
    {
        return User::query()->role([RoleEnum::ADMIN, RoleEnum::GUEST])->get();
    }


    /**
     * @return Collection
     */
    public function getBannedUser(): Collection
    {
        return User::query()->role(RoleEnum::BANNED->value)->get();
    }


    /**
     * @return Collection
     */
    public function getDeletedUser(): Collection
    {
        return User::onlyTrashed()->whereHas('roles', function ($query) {
            $query->whereIn('name', RoleEnum::getAdmin());
        })->get();
    }
}
