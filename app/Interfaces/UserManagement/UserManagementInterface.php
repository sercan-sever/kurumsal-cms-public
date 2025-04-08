<?php

declare(strict_types=1);

namespace App\Interfaces\UserManagement;

use App\DTO\Backend\UserManagement\UserManagementBannedDTO;
use App\DTO\Backend\UserManagement\UserManagementBannedRestoreDTO;
use App\DTO\Backend\UserManagement\UserManagementCreateDTO;
use App\DTO\Backend\UserManagement\UserManagementDeleteDTO;
use App\DTO\Backend\UserManagement\UserManagementTrashedRestoreDTO;
use App\DTO\Backend\UserManagement\UserManagementUpdateDTO;
use App\DTO\Backend\UserManagement\UserManagementUpdatePasswordDTO;
use App\DTO\Backend\UserManagement\UserManagementUpdatePermissionDTO;
use App\Models\User;

interface UserManagementInterface
{
    /**
     * @param UserManagementCreateDTO $userManagementCreateDTO
     *
     * @return User|null
     */
    public function createUserManagement(UserManagementCreateDTO $userManagementCreateDTO): ?User;


    /**
     * @param UserManagementUpdateDTO $userManagementUpdateDTO
     *
     * @return User|null
     */
    public function updateUserManagement(UserManagementUpdateDTO $userManagementUpdateDTO): ?User;


    /**
     * @param UserManagementUpdatePasswordDTO $userManagementUpdatePasswordDTO
     *
     * @return User|null
     */
    public function updatePasswordUserManagement(UserManagementUpdatePasswordDTO $userManagementUpdatePasswordDTO): ?User;


    /**
     * @param UserManagementUpdatePermissionDTO $userManagementUpdatePermissionDTO
     *
     * @return User|null
     */
    public function updatePermissionUserManagement(UserManagementUpdatePermissionDTO $userManagementUpdatePermissionDTO): ?User;


    /**
     * @param UserManagementDeleteDTO $userManagementDeleteDTO
     *
     * @return User|null
     */
    public function deleteUserManagement(UserManagementDeleteDTO $userManagementDeleteDTO): ?User;


    /**
     * @param UserManagementTrashedRestoreDTO $userManagementTrashedRestoreDTO
     *
     * @return User|null
     */
    public function trashedRestoreUserManagement(UserManagementTrashedRestoreDTO $userManagementTrashedRestoreDTO): ?User;


    /**
     * @param UserManagementBannedDTO $userManagementBannedDTO
     *
     * @return User|null
     */
    public function bannedUserManagement(UserManagementBannedDTO $userManagementBannedDTO): ?User;


    /**
     * @param UserManagementBannedRestoreDTO $userManagementBannedRestoreDTO
     *
     * @return User|null
     */
    public function bannedRestoreUserManagement(UserManagementBannedRestoreDTO $userManagementBannedRestoreDTO): ?User;
}
