<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Setting;

use App\DTO\Backend\Settings\Subscribe\SubscribeUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Subscribe\UpdateSubscribeRequest;
use App\Services\Backend\Settings\SettingService;
use App\Services\Backend\Settings\Subscribe\SubscribeService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SubscribeSettingController extends Controller
{
    /**
     * @return void
     */
    public function __construct(
        private readonly SettingService $settingService,
        private readonly SubscribeService $subscribeService,
    ) {
        //
    }


    /**
     * @param UpdateSubscribeRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateSubscribeRequest $request)
    {
        try {
            $subscribeUpdateDTO = SubscribeUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $setting = $this->settingService->getSetting();

            $subscribe = $this->subscribeService->createOrUpdate(subscribeUpdateDTO: $subscribeUpdateDTO, settingId: $setting->id);

            if (empty($subscribe->id)) {
                throw new CustomException('Abone Aktiflik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Abone Aktiflik Başarıyla Güncellendi.',
                'subscribe' => $subscribe,
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
}
