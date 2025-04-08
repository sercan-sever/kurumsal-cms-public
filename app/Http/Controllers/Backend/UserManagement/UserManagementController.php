<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\UserManagement;

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
use App\Exceptions\CustomException;
use App\Http\Requests\UserManagement\CreateUserManagementRequest;
use App\Http\Requests\UserManagement\DeleteUserManagementRequest;
use App\Http\Requests\UserManagement\GetByActiveUserManagementRequest;
use App\Http\Requests\UserManagement\GetByDeleteUserManagementRequest;
use App\Http\Requests\UserManagement\TrashedRestoreUserManagementRequest;
use App\Http\Requests\UserManagement\UpdatePasswordUserManagementRequest;
use App\Http\Requests\UserManagement\UpdatePermissionUserManagementRequest;
use App\Http\Requests\UserManagement\UpdateUserManagementRequest;
use App\Http\Requests\UserManagement\BannedUserManagementRequest;


use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\BannedRestoreUserManagementRequest;
use App\Http\Requests\UserManagement\GetByBannedUserManagementRequest;
use App\Jobs\SendUserCreateEmailJob;
use App\Jobs\SendUserResetPasswordEmailJob;
use App\Services\Backend\Settings\Email\EmailService;
use App\Services\Backend\UserManagements\UserManagementService;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    /**
     * @param UserManagementService $userManagementService
     * @param EmailService $emailService
     *
     * @return void
     */
    public function __construct(
        private readonly UserManagementService $userManagementService,
        private readonly EmailService $emailService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(Request $request): View
    {
        $activeRoles  = $this->userManagementService->listService->getActiveUser(user: $request->user());

        $deletedRoles = $this->userManagementService->listService->getDeletedUser(user: $request->user());

        $bannedRoles  = $this->userManagementService->listService->getBannedUser(user: $request->user());

        $user         = $request->user();

        return view('backend.modules.user-management.user-management', compact('activeRoles', 'deletedRoles', 'bannedRoles', 'user'));
    }


    /**
     * @param GetByActiveUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function readActiveView(GetByActiveUserManagementRequest $request): JsonResponse
    {
        $userManagementGetByActiveUserDTO = UserManagementGetByActiveUserDTO::fromRequest(request: $request);

        $user = $this->userManagementService->readActiveView(userManagementGetByActiveUserDTO: $userManagementGetByActiveUserDTO);

        return !empty($user->id)

            ? response()->json([
                'success'        => true,
                'userManagement' => view('components.backend.user-management.update-view', compact('user'))->render(),
                'message'        => 'Yetkili Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Yetkili Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param GetByDeleteUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function readDeletedView(GetByDeleteUserManagementRequest $request): JsonResponse
    {
        $userManagementGetByDeleteUserDTO = UserManagementGetByDeleteUserDTO::fromRequest(request: $request);

        $user = $this->userManagementService->readDeletedView(userManagementGetByDeleteUserDTO: $userManagementGetByDeleteUserDTO);

        return !empty($user->id)

            ? response()->json([
                'success'        => true,
                'userManagement' => view('components.backend.user-management.update-view', compact('user'))->render(),
                'message'        => 'Yetkili Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Yetkili Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param GetByBannedUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function readBannedView(GetByBannedUserManagementRequest $request): JsonResponse
    {
        $userManagementGetByBannedUserDTO = UserManagementGetByBannedUserDTO::fromRequest(request: $request);

        $user = $this->userManagementService->readBannedView(userManagementGetByBannedUserDTO: $userManagementGetByBannedUserDTO);

        return !empty($user->id)

            ? response()->json([
                'success'        => true,
                'userManagement' => view('components.backend.user-management.update-view', compact('user'))->render(),
                'message'        => 'Yetkili Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Yetkili Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param CreateUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateUserManagementRequest $request): JsonResponse
    {
        try {
            $userManagementCreateDTO = UserManagementCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $userManagement = $this->userManagementService->createUserManagement(userManagementCreateDTO: $userManagementCreateDTO);

            if (empty($userManagement->id)) {
                throw new CustomException('Yetkili Eklemede İşleminde Bir Sorun Oluştu !!!');
            }

            if ($userManagementCreateDTO->sendEmail) {
                $emailSetting = $this->emailService->getModel();

                if (!$this->emailService->syncMailConfigWithDatabaseCheck(emailSetting: $emailSetting)) {
                    throw new CustomException("E-Mail Ayarları Tanımlanmamış Olabilir Kontrol Ediniz !!!");
                }

                SendUserCreateEmailJob::dispatch(
                    user: $userManagement,
                    emailSetting: $emailSetting,
                    password: $userManagementCreateDTO->password
                );
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Yetkili Başarıyla Eklendi.',
                'isAdmin'  => $userManagement->isAdmin(),
                'html'     => $userManagement->getRoleHtml(),
                'user'     => $userManagement,
                'image'    => $userManagement->getImageHtml(),
                'isTrue'   => ($request->user()->id == $userManagement->id),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * @param UpdateUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateUserManagementRequest $request): JsonResponse
    {
        try {
            $userManagementUpdateDTO = UserManagementUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $userManagement = $this->userManagementService->updateUserManagement(userManagementUpdateDTO: $userManagementUpdateDTO);

            if (empty($userManagement->id)) {
                throw new CustomException('Yetkili Günceleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Yetkili Başarıyla Güncellendi.',
                'user'     => $userManagement,
                'image'    => $userManagement->getImage(),
                'name'     => $userManagement->getLimitName(),
                'email'    => $userManagement->getLimitEmail(),
                'isTrue'   => ($request->user()->id == $userManagement->id),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * @param UpdatePasswordUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function updatePassword(UpdatePasswordUserManagementRequest $request): JsonResponse
    {
        try {
            $userManagementUpdatePasswordDTO = UserManagementUpdatePasswordDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $userManagement = $this->userManagementService->updatePasswordUserManagement(userManagementUpdatePasswordDTO: $userManagementUpdatePasswordDTO);

            if (empty($userManagement->id)) {
                throw new CustomException('Yetkili Şifre Günceleme İşleminde Bir Sorun Oluştu !!!');
            }

            if ($userManagementUpdatePasswordDTO->sendEmail) {
                $emailSetting = $this->emailService->getModel();

                if (!$this->emailService->syncMailConfigWithDatabaseCheck(emailSetting: $emailSetting)) {
                    throw new CustomException("E-Mail Ayarları Tanımlanmamış Olabilir Kontrol Ediniz !!!");
                }

                SendUserResetPasswordEmailJob::dispatch(
                    user: $userManagement,
                    emailSetting: $emailSetting,
                    password: $userManagementUpdatePasswordDTO->password
                );
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Yetkili Şifre Başarıyla Güncellendi.',
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * @param UpdatePermissionUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function updatePermission(UpdatePermissionUserManagementRequest $request): JsonResponse
    {
        try {
            $userManagementUpdatePermissionDTO = UserManagementUpdatePermissionDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $userManagement = $this->userManagementService->updatePermissionUserManagement(userManagementUpdatePermissionDTO: $userManagementUpdatePermissionDTO);

            if (empty($userManagement->id)) {
                throw new CustomException('Yetkili İzni Günceleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Yetkili İzni Başarıyla Güncellendi.',
                'user'     => $userManagement,
                'html'     => $userManagement->getRoleHtml(),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * @param DeleteUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteUserManagementRequest $request): JsonResponse
    {
        try {
            $userManagementDeleteDTO = UserManagementDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $userManagement = $this->userManagementService->deleteUserManagement(userManagementDeleteDTO: $userManagementDeleteDTO);

            if (empty($userManagement->id)) {
                throw new CustomException('Yetkili Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Yetkili Başarıyla Silindi.',
                'user'     => $userManagement,
                'html'     => $userManagement->getRoleHtml(),
                'image'    => $userManagement->getImageHtml(),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param TrashedRestoreUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(TrashedRestoreUserManagementRequest $request): JsonResponse
    {
        try {
            $userManagementTrashedRestoreDTO = UserManagementTrashedRestoreDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $userManagement = $this->userManagementService->trashedRestoreUserManagement(userManagementTrashedRestoreDTO: $userManagementTrashedRestoreDTO);

            if (empty($userManagement->id)) {
                throw new CustomException('Yetkili Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Yetkili Başarıyla Geri Getirildi.',
                'user'     => $userManagement,
                'html'     => $userManagement->getRoleHtml(),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * @param BannedUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function banned(BannedUserManagementRequest $request): JsonResponse
    {
        try {
            $userManagementBannedDTO = UserManagementBannedDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $userManagement = $this->userManagementService->bannedUserManagement(userManagementBannedDTO: $userManagementBannedDTO);

            if (empty($userManagement->id)) {
                throw new CustomException('Yetkili Banlama İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Yetkili Başarıyla Banlandı.',
                'user'     => $userManagement,
                'html'     => $userManagement->getRoleHtml(),
                'image'    => $userManagement->getImageHtml(),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * @param BannedRestoreUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function bannedRestore(BannedRestoreUserManagementRequest $request)
    {
        try {
            $userManagementBannedRestoreDTO = UserManagementBannedRestoreDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $userManagement = $this->userManagementService->bannedRestoreUserManagement(userManagementBannedRestoreDTO: $userManagementBannedRestoreDTO);

            if (empty($userManagement->id)) {
                throw new CustomException('Yetkili Aktifleştirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Yetkili Başarıyla Aktif Edildi.',
                'user'     => $userManagement,
                'html'     => $userManagement->getRoleHtml(),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }




    /**
     * @param CreateUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function getByActiveUser(GetByActiveUserManagementRequest $request): JsonResponse
    {
        $userManagementGetByActiveUserDTO = UserManagementGetByActiveUserDTO::fromRequest(request: $request);

        $userManagement = $this->userManagementService->listService->getActiveUserById(id: $userManagementGetByActiveUserDTO->id);

        return !empty($userManagement)

            ? response()->json([
                'success'  => true,
                'message'  => 'Yetkili Başarıyla Getirildi.',
                'isGuest'  => $request->user()->isGuest(),
                'image'    => $userManagement->getImage(),
                'user'     => $userManagement,
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Yetkili Getirmede Bir Sorun Oluştu. Daha Sonra Tekrar Deneyiniz !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }



    /**
     * @param CreateUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function getByActiveAuthorizedUser(GetByActiveUserManagementRequest $request): JsonResponse
    {
        $userManagementGetByActiveUserDTO = UserManagementGetByActiveUserDTO::fromRequest(request: $request);

        $userManagement = $this->userManagementService->listService->getAuthorizedUserById(id: $userManagementGetByActiveUserDTO->id);

        return !empty($userManagement)

            ? response()->json([
                'success'     => true,
                'message'     => 'Yetkili Başarıyla Getirildi.',
                'isGuest'     => $request->user()->isGuest(),
                'image'       => $userManagement->getImageHtml(),
                'user'        => $userManagement,
                'roles'       => $userManagement->roles,
                'permissions' => $userManagement->permissions,
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Yetkili Getirmede Bir Sorun Oluştu. Daha Sonra Tekrar Deneyiniz !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    /**
     * @param GetByDeleteUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function getByDeleteUser(GetByDeleteUserManagementRequest $request): JsonResponse
    {
        $userManagementGetByDeleteUserDTO = UserManagementGetByDeleteUserDTO::fromRequest(request: $request);

        $userManagement = $this->userManagementService->listService->getDeletedUserById(id: $userManagementGetByDeleteUserDTO->id);

        return !empty($userManagement)

            ? response()->json([
                'success'  => true,
                'message'  => 'Yetkili Başarıyla Getirildi.',
                'user'     => $userManagement,
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Yetkili Getirmede Bir Sorun Oluştu. Daha Sonra Tekrar Deneyiniz !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    /**
     * @param GetByBannedUserManagementRequest $request
     *
     * @return JsonResponse
     */
    public function getByBannedUser(GetByBannedUserManagementRequest $request): JsonResponse
    {
        $userManagementGetByBannedUserDTO = UserManagementGetByBannedUserDTO::fromRequest(request: $request);

        $userManagement = $this->userManagementService->listService->getBannedUserById(id: $userManagementGetByBannedUserDTO->id);

        return !empty($userManagement)

            ? response()->json([
                'success'  => true,
                'message'  => 'Yetkili Başarıyla Getirildi.',
                'user'     => $userManagement,
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Yetkili Getirmede Bir Sorun Oluştu. Daha Sonra Tekrar Deneyiniz !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
