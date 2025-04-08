<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Setting;

use App\DTO\Backend\Settings\Logo\LogoUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Logo\FaviconRequest;
use App\Http\Requests\Settings\Logo\FooterLogoRequest;
use App\Http\Requests\Settings\Logo\HeaderLogoRequest;
use App\Http\Requests\Settings\Logo\IdLogoRequest;
use App\Services\Backend\Settings\Logo\LogoService;
use App\Services\Backend\Settings\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LogoSettingController extends Controller
{
    /**
     * @param SettingService $settingService
     * @param LogoService $logoService
     *
     * @return void
     */
    public function __construct(
        private readonly SettingService $settingService,
        private readonly LogoService $logoService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function logo(): View
    {
        $setting = $this->settingService->getSetting();
        $logo = $this->logoService->getLogo();

        return view('backend.modules.settings.logo-setting', compact('setting', 'logo'));
    }


    /**
     * @param IdLogoRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdLogoRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $logo = $this->logoService->getLogoById(id: (int)$valid['id']);

        return !empty($logo->id)

            ? response()->json([
                'success' => true,
                'title'   => $logo?->updatedBy?->name,
                'email'   => $logo?->updatedBy?->email,
                'statu'   => $logo?->updatedBy?->getRoleHtml(),
                'date'    => $logo?->getUpdatedAt(),
                'message' => 'Logo Ayarları Son Güncelleme Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Logo Ayarları Son Güncelleme Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param FaviconRequest $request
     *
     * @return JsonResponse
     */
    public function favicon(FaviconRequest $request): JsonResponse
    {
        try {
            $logoUpdateDTO = LogoUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $favicon = $this->logoService->createOrUpdateFavicon(logoUpdateDTO: $logoUpdateDTO);

            if (empty($favicon->id)) {
                throw new CustomException('Favicon Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success'     => true,
                'message'     => 'Favicon Başarıyla Güncellendi.',
                'logoSetting' => $favicon,
                'favicon'     => $favicon->getBackendFavicon(),
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
     * @param HeaderLogoRequest $request
     *
     * @return JsonResponse
     */
    public function headerWhite(HeaderLogoRequest $request): JsonResponse
    {
        try {
            $logoUpdateDTO = LogoUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $headerWhite = $this->logoService->createOrUpdateHeaderWhite(logoUpdateDTO: $logoUpdateDTO);

            if (empty($headerWhite->id)) {
                throw new CustomException('Header Beyaz Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Header Beyaz Başarıyla Güncellendi.',
                'logoSetting'  => $headerWhite,
                'headerWhite'  => $headerWhite->getBackendHeaderWhite(),
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
     * @param HeaderLogoRequest $request
     *
     * @return JsonResponse
     */
    public function headerDark(HeaderLogoRequest $request): JsonResponse
    {
        try {
            $logoUpdateDTO = LogoUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $headerDark = $this->logoService->createOrUpdateHeaderDark(logoUpdateDTO: $logoUpdateDTO);

            if (empty($headerDark->id)) {
                throw new CustomException('Header Koyu Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success'     => true,
                'message'     => 'Header Koyu Başarıyla Güncellendi.',
                'logoSetting' => $headerDark,
                'headerDark'  => $headerDark->getBackendHeaderDark(),
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
     * @param FooterLogoRequest $request
     *
     * @return JsonResponse
     */
    public function footerWhite(FooterLogoRequest $request): JsonResponse
    {
        try {
            $logoUpdateDTO = LogoUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $footerWhite = $this->logoService->createOrUpdateFooterWhite(logoUpdateDTO: $logoUpdateDTO);

            if (empty($footerWhite->id)) {
                throw new CustomException('Footer Beyaz Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Footer Beyaz Başarıyla Güncellendi.',
                'logoSetting'  => $footerWhite,
                'footerWhite'  => $footerWhite->getBackendFooterWhite(),
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
     * @param FooterLogoRequest $request
     *
     * @return JsonResponse
     */
    public function footerDark(FooterLogoRequest $request): JsonResponse
    {
        try {
            $logoUpdateDTO = LogoUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $footerDark = $this->logoService->createOrUpdateFooterDark(logoUpdateDTO: $logoUpdateDTO);

            if (empty($footerDark->id)) {
                throw new CustomException('Footer Koyu Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success'     => true,
                'message'     => 'Footer Koyu Başarıyla Güncellendi.',
                'logoSetting' => $footerDark,
                'footerDark'  => $footerDark->getBackendFooterDark(),
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
