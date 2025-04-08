<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Setting;

use App\DTO\Backend\Settings\Plugin\PluginUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Plugin\IdPluginRequest;
use App\Http\Requests\Settings\Plugin\UpdatePluginRequest;
use App\Services\Backend\Settings\Plugin\PluginService;
use App\Services\Backend\Settings\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PluginSettingController extends Controller
{
    /**
     * @param SettingService $settingService
     * @param PluginService $pluginService
     *
     * @return void
     */
    public function __construct(
        private readonly SettingService $settingService,
        private readonly PluginService $pluginService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function plugin(): View
    {
        $setting = $this->settingService->getSetting();
        $plugin  = $this->pluginService->getModel();

        return view('backend.modules.settings.plugin-setting', compact('setting', 'plugin'));
    }


    /**
     * @param UpdatePluginRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdatePluginRequest $request)
    {
        try {
            $pluginDTO = PluginUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $plugin = $this->pluginService->createOrUpdate(pluginUpdateDTO: $pluginDTO);

            if (empty($plugin->id)) {
                throw new CustomException('Eklenti Ayarları Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Eklenti Ayarları Başarıyla Güncellendi.',
                'plugin'  => $plugin,
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
     * @param IdPluginRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdPluginRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $plugin = $this->pluginService->getPluginById(id: (int)$valid['id']);

        return !empty($plugin->id)

            ? response()->json([
                'success' => true,
                'title'   => $plugin?->updatedBy?->name,
                'email'   => $plugin?->updatedBy?->email,
                'statu'   => $plugin?->updatedBy?->getRoleHtml(),
                'date'    => $plugin?->getUpdatedAt(),
                'message' => 'Eklenti Ayarları Son Güncelleme Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Eklenti Ayarları Son Güncelleme Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }
}
