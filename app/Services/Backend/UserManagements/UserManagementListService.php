<?php

declare(strict_types=1);

namespace App\Services\Backend\UserManagements;

use App\Models\User;
use Illuminate\Support\Collection;
use App\Enums\Roles\RoleEnum;
use App\Interfaces\UserManagement\UserManagementListInterface;
use App\Services\Backend\UserManagements\Lists\UserManagementAdminListService;
use App\Services\Backend\UserManagements\Lists\UserManagementGuestListService;
use App\Services\Backend\UserManagements\Lists\UserManagementSuperAdminListService;

class UserManagementListService implements UserManagementListInterface
{

    /**
     * @param UserManagementSuperAdminListService $superAdmin
     * @param UserManagementAdminListService $admin
     * @param UserManagementGuestListService $guest
     *
     * @return void
     */
    public function __construct(
        public readonly UserManagementSuperAdminListService $superAdmin,
        public readonly UserManagementAdminListService $admin,
        public readonly UserManagementGuestListService $guest,
    ) {
        //
    }

    /* ACTIVE */
    /**
     * @param User $user
     *
     * @return Collection
     */
    public function getActiveUser(User $user): Collection
    {
        if ($user->hasRole(RoleEnum::SUPER_ADMIN)) {
            return $this->superAdmin->getActiveUsers();
        }

        if ($user->hasRole(RoleEnum::ADMIN)) {
            return $this->admin->getActiveUsers();
        }

        if ($user->hasRole(RoleEnum::GUEST)) {
            return $this->guest->getByGuestUser($user->id);
        }

        return collect(); // Hiçbir rol yoksa boş döndür
    }

    /**
     * @param int $id
     *
     * @return User|null
     */
    public function getActiveUserById(int $id): ?User
    {
        return User::query()->with('roles')->role(RoleEnum::getAdmin())->where('id', $id)->first();
    }

    /**
     * @param int $id
     *
     * @return User|null
     */
    public function getAuthorizedUserById(int $id): ?User
    {
        $user = User::query()->role(RoleEnum::getAdmin())->where('id', $id)->first();

        if (!$this->canAuthorizedUserCheck(request()->user(), $user)) {
            return null;
        }

        return $user;
    }

    /**
     * @param User $authUser
     * @param User|null $targetUser
     *
     * @return bool
     */
    private function canAuthorizedUserCheck(User $authUser, ?User $targetUser): bool
    {
        if (empty($targetUser)) {
            return false;
        }

        if ($authUser->isBanned()) {
            return false;
        }

        if ($authUser->isGuest()) {
            return false;
        }

        if ($authUser->isAdmin() && $authUser->id == $targetUser->id && ($targetUser->isAdmin() || $targetUser->isSuperAdmin())) {
            return false;
        }

        if ($authUser->isSuperAdmin() && $authUser->id == $targetUser->id && $targetUser->isSuperAdmin()) {
            return false;
        }

        return true;
    }



    /* DELETED */
    /**
     * @param User $user
     *
     * @return Collection
     */
    public function getDeletedUser(User $user): Collection
    {
        if ($user->hasRole(RoleEnum::SUPER_ADMIN)) {
            return $this->superAdmin->getDeletedUser();
        }

        if ($user->hasRole(RoleEnum::ADMIN)) {
            return $this->admin->getDeletedUser();
        }

        return collect(); // Hiçbir rol yoksa boş döndür
    }

    /**
     * @param int $id
     *
     * @return User|null
     */
    public function getDeletedUserById(int $id): ?User
    {
        return User::onlyTrashed()->with('roles')->whereHas('roles', function ($query) {
            $query->whereIn('name', RoleEnum::getAdmin());
        })->where('id', $id)->first();
    }



    /* BANNED */
    /**
     * @param User $user
     *
     * @return Collection
     */
    public function getBannedUser(User $user): Collection
    {
        if ($user->hasRole(RoleEnum::SUPER_ADMIN)) {
            return $this->superAdmin->getBannedUser();
        }

        if ($user->hasRole(RoleEnum::ADMIN)) {
            return $this->admin->getBannedUser();
        }

        return collect(); // Hiçbir rol yoksa boş döndür
    }

    /**
     * @param int $id
     *
     * @return User|null
     */
    public function getBannedUserById(int $id): ?User
    {
        return User::query()->with('roles')->role([RoleEnum::BANNED->value])->where('id', $id)->first();
    }
}
