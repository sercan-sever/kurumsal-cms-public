<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Setting;

use App\DTO\Backend\Settings\Social\SocialUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Social\IdSocialRequest;
use App\Http\Requests\Settings\Social\UpdateSocialRequest;
use App\Services\Backend\Settings\SettingService;
use App\Services\Backend\Settings\Social\SocialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SocialSettingController extends Controller
{
    /**
     * @param SettingService $settingService
     * @param SocialService $socialService
     *
     * @return void
     */
    public function __construct(
        private readonly SettingService $settingService,
        private readonly SocialService $socialService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function social(): View
    {
        $setting = $this->settingService->getSetting();
        $social = $this->socialService->getModel();

        return view('backend.modules.settings.social-setting', compact('setting', 'social'));
    }


    /**
     * @param UpdateSocialRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateSocialRequest $request)
    {
        try {
            $socialdDTO = SocialUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $social = $this->socialService->createOrUpdate(socialUpdateDTO: $socialdDTO);

            if (empty($social->id)) {
                throw new CustomException('Sosyal Medya Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sosyal Medya Başarıyla Güncellendi.',
                'social'  => $social,
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
     * @param IdSocialRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdSocialRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $social = $this->socialService->getSocialById(id: (int)$valid['id']);

        return !empty($social->id)

            ? response()->json([
                'success' => true,
                'title'   => $social?->updatedBy?->name,
                'email'   => $social?->updatedBy?->email,
                'statu'   => $social?->updatedBy?->getRoleHtml(),
                'date'    => $social?->getUpdatedAt(),
                'message' => 'Sosyal Medya Ayarları Son Güncelleme Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Sosyal Medya Ayarları Son Güncelleme Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }
}
