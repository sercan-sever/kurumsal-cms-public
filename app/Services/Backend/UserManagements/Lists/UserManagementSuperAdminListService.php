<?php

declare(strict_types=1);

namespace App\Services\Backend\UserManagements\Lists;

use App\Enums\Roles\RoleEnum;
use App\Models\User;
use Illuminate\Support\Collection;

class UserManagementSuperAdminListService
{
    /**
     * @return Collection
     */
    public function getActiveUsers(): Collection
    {
        return User::query()->with('roles')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', RoleEnum::getAdmin());
            })
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', RoleEnum::getNotLogin());
            })
            ->get();
    }


    /**
     * @return Collection
     */
    public function getBannedUser(): Collection
    {
        return User::query()->with('roles')->whereHas('roles', function ($query) {
            $query->whereIn('name', [RoleEnum::BANNED->value]);
        })->get();
    }


    /**
     * @return Collection
     */
    public function getDeletedUser(): Collection
    {
        return User::onlyTrashed()->with('roles')->whereHas('roles', function ($query) {
            $query->whereIn('name', RoleEnum::getAdmin());
        })->get();
    }
}
