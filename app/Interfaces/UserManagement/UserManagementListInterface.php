<?php

declare(strict_types=1);

namespace App\Interfaces\UserManagement;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserManagementListInterface
{
    /**
     * @param User $user
     *
     * @return Collection
     */
    public function getActiveUser(User $user): Collection;


    /**
     * @param int $id
     *
     * @return User|null
     */
    public function getActiveUserById(int $id): ?User;


    /**
     * @param int $id
     *
     * @return User|null
     */
    public function getAuthorizedUserById(int $id): ?User;


    /**
     * @param User $user
     *
     * @return Collection
     */
    public function getDeletedUser(User $user): Collection;


    /**
     * @param int $id
     *
     * @return User|null
     */
    public function getDeletedUserById(int $id): ?User;


    /**
     * @param User $user
     *
     * @return Collection
     */
    public function getBannedUser(User $user): Collection;


    /**
     * @param int $id
     *
     * @return User|null
     */
    public function getBannedUserById(int $id): ?User;
}
