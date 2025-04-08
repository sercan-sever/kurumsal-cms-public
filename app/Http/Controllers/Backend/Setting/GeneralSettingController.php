<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Setting;

use App\DTO\Backend\Settings\General\GeneralUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\General\IdGeneralRequest;
use App\Http\Requests\Settings\General\UpdateGeneralRequest;
use App\Services\Backend\Settings\General\GeneralService;
use App\Services\Backend\Settings\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GeneralSettingController extends Controller
{
    /**
     * @param SettingService $settingService
     * @param GeneralService $generalService
     *
     * @return void
     */
    public function __construct(
        private readonly SettingService $settingService,
        private readonly GeneralService $generalService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function general(): View
    {
        $setting = $this->settingService->getSetting();
        $general = $this->generalService->getGeneralAllContent();

        return view('backend.modules.settings.general-setting', compact('setting', 'general'));
    }



    /**
     * @param UpdateGeneralRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateGeneralRequest $request)
    {
        try {
            $generalUpdateDTO = GeneralUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $general = $this->generalService->createOrUpdate(generalUpdateDTO: $generalUpdateDTO);

            if (empty($general->id)) {
                throw new CustomException('Genel Ayarlar Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Genel Ayarlar Başarıyla Güncellendi.',
                'general'  => $general,
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
     * @param IdGeneralRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdGeneralRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $general = $this->generalService->getGeneralById(id: (int)$valid['id']);

        return !empty($general->id)

            ? response()->json([
                'success' => true,
                'title'   => $general?->updatedBy?->name,
                'email'   => $general?->updatedBy?->email,
                'statu'   => $general?->updatedBy?->getRoleHtml(),
                'date'    => $general?->getUpdatedAt(),
                'message' => 'Genel Ayarlar Son Güncelleme Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Genel Ayarlar Son Güncelleme Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }
}
