<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Banner;

use App\DTO\Backend\Banner\BannerCreateDTO;
use App\DTO\Backend\Banner\BannerDeleteDTO;
use App\DTO\Backend\Banner\BannerUpdateDTO;

use App\Http\Requests\Banner\CreateBannerRequest;
use App\Http\Requests\Banner\DeleteBannerRequest;
use App\Http\Requests\Banner\IdBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;

use App\Services\Backend\Banner\BannerContentService;
use App\Services\Backend\Banner\BannerService;

use App\Http\Controllers\Controller;

use App\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BannerController extends Controller
{
    /**
     * @param BannerService $bannerService
     * @param BannerContentService $bannerContentService
     *
     * @return void
     */
    public function __construct(
        private readonly BannerService $bannerService,
        private readonly BannerContentService $bannerContentService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $banners = $this->bannerService->getAllModel();
        $deletedBanners = $this->bannerService->getAllDeletedModel();

        return view('backend.modules.banner.banner', compact('banners', 'deletedBanners'));
    }


    /**
     * @param CreateBannerRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateBannerRequest $request): JsonResponse
    {
        try {
            $bannerCreateDTO = BannerCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $banner = $this->bannerService->createModel(bannerCreateDTO: $bannerCreateDTO);

            if (empty($banner->id) || !$bannerCreateDTO->languages) {
                throw new CustomException('Banner Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $bannerResult = $this->bannerContentService->updateOrCreateContent(banner: $banner, languages: $bannerCreateDTO->languages);

            if (empty($bannerResult->id)) {
                throw new CustomException('Banner Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'Banner Başarıyla Eklendi.',
                'banner'        => $banner,
                'bannerContent' => $banner?->content,
                'image'         => $banner->getImageHtml(),
                'status'        => $banner->getStatusInput(),
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
     * @param IdBannerRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(IdBannerRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $bannerStatus = $this->bannerService->changeStatus(id: (int)$valid['id']);

            if (!$bannerStatus) {
                throw new CustomException('Banner Durum Değiştirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Banner Durumu Başarıyla Değiştirildi.',
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
     * @param IdBannerRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdBannerRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $banner = $this->bannerService->getModelById(id: (int)$valid['id']);

        return !empty($banner->id)

            ? response()->json([
                'success' => true,
                'banner'  => view('components.backend.banners.update-view', compact('banner'))->render(),
                'message' => 'Banner Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Banner Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBannerRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdBannerRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $banner = $this->bannerService->getModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($banner->id)

            ? response()->json([
                'success' => true,
                'banner'  => view('components.backend.banners.update-view', compact('banner', 'disabled'))->render(),
                'message' => 'Banner Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Banner Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param UpdateBannerRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateBannerRequest $request): JsonResponse
    {
        try {
            $bannerUpdateDTO = BannerUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $banner = $this->bannerService->updateModel(bannerUpdateDTO: $bannerUpdateDTO);

            if (empty($banner->id) || !$bannerUpdateDTO->languages) {
                throw new CustomException('Banner Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $bannerResult = $this->bannerContentService->updateOrCreateContent(banner: $banner, languages: $bannerUpdateDTO->languages);

            if (empty($bannerResult->id)) {
                throw new CustomException('Banner İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'Banner Başarıyla Güncelledi.',
                'banner'        => $banner,
                'bannerContent' => $banner?->content,
                'image'         => $banner->getImageHtml(),
                'status'        => $banner->getStatusInput(),
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
     * @param DeleteBannerRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteBannerRequest $request): JsonResponse
    {
        try {
            $bannerDeleteDTO = BannerDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $banner = $this->bannerService->deleteModel(bannerDeleteDTO: $bannerDeleteDTO);

            if (empty($banner->id)) {
                throw new CustomException('Banner Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'Banner Başarıyla Silindi.',
                'banner'        => $banner,
                'bannerContent' => $banner?->content,
                'image'         => $banner->getImageHtml(),
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
     * @param IdBannerRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdBannerRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $banner = $this->bannerService->getDeletedModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($banner->id)

            ? response()->json([
                'success' => true,
                'banner'  => view('components.backend.banners.update-view', compact('banner', 'disabled'))->render(),
                'message' => 'Silinen Banner Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Silinen Banner Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBannerRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdBannerRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $banner = $this->bannerService->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($banner->id)) {
                throw new CustomException('Banner Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'Banner Başarıyla Geri Getirildi.',
                'banner'        => $banner,
                'bannerContent' => $banner?->content,
                'image'         => $banner->getImageHtml(),
                'status'        => $banner->getStatusInput(),
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
     * @param IdBannerRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdBannerRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            $banner = $this->bannerService->trashedRemoveModel(id: (int)$valid['id']);

            if (empty($banner)) {
                throw new CustomException('Banner Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Banner Kalıcı Olarak Silindi.',
                'banner'  => $banner,
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
