<?php

declare(strict_types=1);

namespace App\Services\Backend\UserManagements;

use App\DTO\Backend\UserManagement\UserManagementBannedDTO;
use App\DTO\Backend\UserManagement\UserManagementBannedRestoreDTO;
use App\DTO\Backend\UserManagement\UserManagementCreateDTO;
use App\DTO\Backend\UserManagement\UserManagementDeleteDTO;
use App\DTO\Backend\UserManagement\UserManagementGetByActiveUserDTO;
use App\DTO\Backend\UserManagement\UserManagementGetByBannedUserDTO;
use App\DTO\Backend\UserManagement\UserManagementGetByDeleteUserDTO;
use App\DTO\Backend\UserManagement\UserManagementTrashedRestoreDTO;
use App\DTO\Backend\UserManagement\UserManagementUpdateDTO;
use App\DTO\Backend\UserManagement\UserManagementUpdatePasswordDTO;
use App\DTO\Backend\UserManagement\UserManagementUpdatePermissionDTO;
use App\Interfaces\UserManagement\UserManagementInterface;
use App\Models\User;
use App\Services\Backend\UserRoleAndPermission\UserRoleAndPermissionService;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserManagementService implements UserManagementInterface
{
    use ImageUpload, ImageDelete;

    /**
     * @param UserManagementListService $listService
     *
     * @return void
     */
    public function __construct(
        public readonly UserManagementListService $listService,
        public readonly UserRoleAndPermissionService $userRoleAndPermissionService
    ) {
        //
    }


    /**
     * @param UserManagementGetByActiveUserDTO $userManagementGetByActiveUserDTO
     *
     * @return User|null
     */
    public function readActiveView(UserManagementGetByActiveUserDTO $userManagementGetByActiveUserDTO): ?User
    {
        $user = $this->listService->getActiveUserById(id: $userManagementGetByActiveUserDTO->id);

        if (!$this->updateUserCheck(authUser: request()->user(), targetUser: $user)) {
            return null;
        }

        return !empty($user->id) ? $user : null;
    }


    /**
     * @param UserManagementGetByDeleteUserDTO $userManagementGetByDeleteUserDTO
     *
     * @return User|null
     */
    public function readDeletedView(UserManagementGetByDeleteUserDTO $userManagementGetByDeleteUserDTO): ?User
    {
        $user = $this->listService->getDeletedUserById(id: $userManagementGetByDeleteUserDTO->id);

        if (!$this->updateUserCheck(authUser: request()->user(), targetUser: $user)) {
            return null;
        }

        return !empty($user->id) ? $user : null;
    }


    /**
     * @param UserManagementGetByBannedUserDTO $userManagementGetByBannedUserDTO
     *
     * @return User|null
     */
    public function readBannedView(UserManagementGetByBannedUserDTO $userManagementGetByBannedUserDTO): ?User
    {
        $user = $this->listService->getBannedUserById(id: $userManagementGetByBannedUserDTO->id);

        if (!$this->updateUserCheck(authUser: request()->user(), targetUser: $user)) {
            return null;
        }

        return !empty($user->id) ? $user : null;
    }


    /**
     * @param UserManagementCreateDTO $userManagementCreateDTO
     *
     * @return User|null
     */
    public function createUserManagement(UserManagementCreateDTO $userManagementCreateDTO): ?User
    {
        try {
            $image = $this->handleImageUpdate(user: null, image: $userManagementCreateDTO?->image);

            $user = User::query()->create([
                'name'       => $userManagementCreateDTO->name,
                'email'      => $userManagementCreateDTO->email,
                'phone'      => $userManagementCreateDTO?->phone,
                'password'   => Hash::make(value: passwordGeneration(password: $userManagementCreateDTO->password)),
                'image'      => $image['image'],
                'type'       => $image['type'],
                'created_by' => request()->user()->id,
            ]);

            $result = $this->userRoleAndPermissionService->createAssignRolesAndPermissions(
                user: $user,
                admin: $userManagementCreateDTO->admin,
                permissions: $userManagementCreateDTO->permissions
            );

            return $result ? $user : null;
        } catch (\Exception $exception) {
            Log::error(message: 'UserManagementService (createUserManagement) : ', context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param UserManagementUpdateDTO $userManagementUpdateDTO
     *
     * @return User|null
     */
    public function updateUserManagement(UserManagementUpdateDTO $userManagementUpdateDTO): ?User
    {
        try {
            $user = $this->listService->getActiveUserById(id: $userManagementUpdateDTO->id);

            if (!$this->updateUserCheck(authUser: request()->user(), targetUser: $user)) {
                return null;
            }

            $image = $this->handleImageUpdate(user: $user, image: $userManagementUpdateDTO?->image);

            $result = $user->update([
                'name'       => $userManagementUpdateDTO->name,
                'email'      => $userManagementUpdateDTO->email,
                'phone'      => $userManagementUpdateDTO?->phone,
                'image'      => $image['image'],
                'type'       => $image['type'],
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            return !empty($result) ? $user : null;
        } catch (\Exception $exception) {
            Log::error(message: 'UserManagementService (updateUserManagement) : ', context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param UserManagementUpdatePasswordDTO $userManagementUpdatePasswordDTO
     *
     * @return User|null
     */
    public function updatePasswordUserManagement(UserManagementUpdatePasswordDTO $userManagementUpdatePasswordDTO): ?User
    {
        try {
            $user = $this->listService->getActiveUserById(id: $userManagementUpdatePasswordDTO->id);

            if (!$this->updateUserCheck(authUser: request()->user(), targetUser: $user)) {
                return null;
            }

            $result = $user->update([
                'password'   => passwordGeneration(password: $userManagementUpdatePasswordDTO->password),
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            return !empty($result) ? $user : null;
        } catch (\Exception $exception) {
            Log::error(message: 'UserManagementService (updatePasswordUserManagement) : ', context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param UserManagementUpdatePermissionDTO $userManagementUpdatePermissionDTO
     *
     * @return User|null
     */
    public function updatePermissionUserManagement(UserManagementUpdatePermissionDTO $userManagementUpdatePermissionDTO): ?User
    {
        try {
            $user = $this->listService->getActiveUserById(id: $userManagementUpdatePermissionDTO->id);

            if (!$this->updatePermissionUserCheck(
                isAdmin: $userManagementUpdatePermissionDTO->admin,
                authUser: request()->user(),
                targetUser: $user
            )) {
                return null;
            }

            $result = $this->userRoleAndPermissionService->updateAssignRolesAndPermissions(
                user: $user,
                admin: $userManagementUpdatePermissionDTO->admin,
                permissions: $userManagementUpdatePermissionDTO->permissions,
            );

            if (!$result) {
                return null;
            }

            $updateResult = $user->update([
                'updated_by' => request()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            return $updateResult ? $user : null;
        } catch (\Exception $exception) {
            Log::error(message: 'UserManagementService (updatePermissionUserManagement) : ', context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param UserManagementDeleteDTO $userManagementDeleteDTO
     *
     * @return User|null
     */
    public function deleteUserManagement(UserManagementDeleteDTO $userManagementDeleteDTO): ?User
    {
        try {
            $user = $this->listService->getActiveUserById(id: $userManagementDeleteDTO->id);

            if (!$this->deleteAndBannedUserCheck(
                authUser: request()->user(),
                targetUser: $user
            )) {
                return null;
            }

            $result = $user->update([
                'deleted_description' => $userManagementDeleteDTO->deletedDescription,
                'deleted_by'          => request()->user()->id,
                'deleted_at'          => Carbon::now(),
            ]);

            return $result ? $user : null;
        } catch (\Exception $exception) {
            Log::error(message: 'UserManagementService (deleteUserManagement) : ', context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param UserManagementTrashedRestoreDTO $userManagementTrashedRestoreDTO
     *
     * @return User|null
     */
    public function trashedRestoreUserManagement(UserManagementTrashedRestoreDTO $userManagementTrashedRestoreDTO): ?User
    {
        try {
            $user = $this->listService->getDeletedUserById(id: $userManagementTrashedRestoreDTO->id);

            if (!$this->trashedRestoreUserCheck(
                authUser: request()->user(),
                targetUser: $user
            )) {
                return null;
            }

            $result = $user->update([
                'deleted_by'          => null,
                'deleted_description' => null,
                'deleted_at'          => null,
            ]);

            return $result ? $user : null;
        } catch (\Exception $exception) {
            Log::error(message: 'UserManagementService (trashedRestoreUserManagement) : ', context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param UserManagementBannedDTO $userManagementBannedDTO
     *
     * @return User|null
     */
    public function bannedUserManagement(UserManagementBannedDTO $userManagementBannedDTO): ?User
    {
        try {
            $user = $this->listService->getActiveUserById(id: $userManagementBannedDTO->id);

            if (!$this->deleteAndBannedUserCheck(
                authUser: request()->user(),
                targetUser: $user
            )) {
                return null;
            }

            $result = $this->userRoleAndPermissionService->addBannedRole(user: $user);

            if (!$result) {
                return null;
            }

            $updateResult = $user->update([
                'banned_description' => $userManagementBannedDTO->bannedDescription,
                'banned_by'          => request()->user()->id,
                'banned_at'          => Carbon::now(),
            ]);

            return $updateResult ? $user : null;
        } catch (\Exception $exception) {
            Log::error(message: 'UserManagementService (bannedUserManagement) : ', context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param UserManagementBannedRestoreDTO $userManagementBannedRestoreDTO
     *
     * @return User|null
     */
    public function bannedRestoreUserManagement(UserManagementBannedRestoreDTO $userManagementBannedRestoreDTO): ?User
    {
        try {
            $user = $this->listService->getBannedUserById(id: $userManagementBannedRestoreDTO->id);

            if (!$this->bannedRestoreUserCheck(
                authUser: request()->user(),
                targetUser: $user
            )) {
                return null;
            }

            $result = $this->userRoleAndPermissionService->removeBannedRole(user: $user);

            if (!$result) {
                return null;
            }

            $updateResult = $user->update([
                'banned_description' => null,
                'banned_by'          => null,
                'banned_at'          => null,
            ]);

            return $updateResult ? $user : null;
        } catch (\Exception $exception) {
            Log::error(message: 'UserManagementService (bannedRestoreUserManagement) : ', context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param User $authUser
     * @param User|null $targetUser
     *
     * @return bool
     */
    private function updateUserCheck(User $authUser, ?User $targetUser): bool
    {
        if (empty($targetUser)) {
            return false;
        }

        if ($authUser->isBanned() && $targetUser->isBanned()) {
            return false;
        }

        if ($authUser->isAdmin() && $targetUser->isSuperAdmin()) {
            return false;
        }

        if ($authUser->isAdmin() && $targetUser->isAdmin() && ($authUser->id != $targetUser->id)) {
            return false;
        }

        return true;
    }


    /**
     * @param bool $isAdmin
     * @param User $authUser
     * @param User|null $targetUser
     *
     * @return bool
     */
    private function updatePermissionUserCheck(bool $isAdmin, User $authUser, ?User $targetUser): bool
    {
        if (empty($targetUser)) {
            return false;
        }

        if ($authUser->isBanned() && $targetUser->isBanned()) {
            return false;
        }

        if ($isAdmin && !$authUser->isSuperAdmin()) {
            return false;
        }

        if ($authUser->id == $targetUser->id) {
            return false;
        }

        if ($authUser->isAdmin() && $targetUser->isSuperAdmin()) {
            return false;
        }

        if ($authUser->isAdmin() && $targetUser->isAdmin() && ($authUser->id != $targetUser->id)) {
            return false;
        }

        return true;
    }


    /**
     * @param bool $isAdmin
     * @param User $authUser
     * @param User|null $targetUser
     *
     * @return bool
     */
    private function deleteAndBannedUserCheck(User $authUser, ?User $targetUser): bool
    {
        if (empty($targetUser)) {
            return false;
        }

        if ($authUser->isBanned() && $targetUser->isBanned()) {
            return false;
        }

        if ($authUser->isGuest()) {
            return false;
        }

        if ($authUser->id == $targetUser->id) {
            return false;
        }

        if ($authUser->isAdmin() && $targetUser->isSuperAdmin()) {
            return false;
        }

        if ($authUser->isAdmin() && $targetUser->isAdmin() && ($authUser->id != $targetUser->id)) {
            return false;
        }

        return true;
    }


    /**
     * @param bool $isAdmin
     * @param User $authUser
     * @param User|null $targetUser
     *
     * @return bool
     */
    private function trashedRestoreUserCheck(User $authUser, ?User $targetUser): bool
    {
        if (empty($targetUser)) {
            return false;
        }

        if ($authUser->isBanned() && $targetUser->isBanned()) {
            return false;
        }

        if ($authUser->isGuest()) {
            return false;
        }

        if ($authUser->id == $targetUser->id) {
            return false;
        }

        if ($authUser->isAdmin() && $targetUser->isSuperAdmin()) {
            return false;
        }

        return true;
    }


    /**
     * @param bool $isAdmin
     * @param User $authUser
     * @param User|null $targetUser
     *
     * @return bool
     */
    private function bannedRestoreUserCheck(User $authUser, ?User $targetUser): bool
    {
        if (empty($targetUser)) {
            return false;
        }

        if ($authUser->isBanned() && $targetUser->isBanned()) {
            return false;
        }

        if ($authUser->isGuest()) {
            return false;
        }

        if ($authUser->id == $targetUser->id) {
            return false;
        }

        if ($authUser->isAdmin() && $targetUser->isSuperAdmin()) {
            return false;
        }

        return true;
    }


    /**
     * @param User|null $user
     * @param UploadedFile|null $image
     *
     * @return array<string,string|null>
     */
    private function handleImageUpdate(?User $user, ?UploadedFile $image): array
    {
        $image_ = ['image' => $user?->image, 'type'  => $user?->type];

        if (!empty($image)) {
            if (!empty($user->image)) {
                $this->imageDelete(image: $user->image);
            }

            $image_ = $this->imageUpload(
                file: $image,
                path: 'users',
                width: 100,
                height: 100
            );
        }

        return $image_;
    }
}
