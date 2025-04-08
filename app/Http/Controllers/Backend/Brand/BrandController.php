<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Brand;

use App\DTO\Backend\Brand\BrandCreateDTO;
use App\DTO\Backend\Brand\BrandDeleteDTO;
use App\DTO\Backend\Brand\BrandUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\CreateBrandRequest;
use App\Http\Requests\Brand\DeleteBrandRequest;
use App\Http\Requests\Brand\IdBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Services\Backend\Brand\BrandContentService;
use App\Services\Backend\Brand\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BrandController extends Controller
{
    /**
     * @param BrandService $brandService
     * @param BrandContentService $brandContentService
     *
     * @return void
     */
    public function __construct(
        private readonly BrandService $brandService,
        private readonly BrandContentService $brandContentService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $brands = $this->brandService->getAllModel();
        $deletedBrands = $this->brandService->getAllDeletedModel();

        return view('backend.modules.brand.brand', compact('brands', 'deletedBrands'));
    }


    /**
     * @param CreateBrandRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateBrandRequest $request): JsonResponse
    {
        try {
            $brandCreateDTO = BrandCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $brand = $this->brandService->createModel(brandCreateDTO: $brandCreateDTO);

            if (empty($brand->id)) {
                throw new CustomException('Marka Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $brandResult = $this->brandContentService->updateOrCreateContent(brand: $brand, languages: $brandCreateDTO->languages);

            if (empty($brandResult->id)) {
                throw new CustomException('Marka Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Marka Başarıyla Eklendi.',
                'brand'        => $brand,
                'brandContent' => $brand?->content,
                'image'        => $brand?->getImageHtml(),
                'status'       => $brand?->getStatusInput(),
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
     * @param IdBrandRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(IdBrandRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $brandStatus = $this->brandService->changeStatus(id: (int)$valid['id']);

            if (!$brandStatus) {
                throw new CustomException('Marka Durum Değiştirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Marka Durumu Başarıyla Değiştirildi.',
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
     * @param IdBrandRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdBrandRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $brand = $this->brandService->getModelById(id: (int)$valid['id']);

        return !empty($brand->id)

            ? response()->json([
                'success' => true,
                'brand'   => view('components.backend.brands.update-view', compact('brand'))->render(),
                'message' => 'Marka Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Marka Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBrandRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdBrandRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $brand = $this->brandService->getModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($brand->id)

            ? response()->json([
                'success' => true,
                'brand'   => view('components.backend.brands.update-view', compact('brand', 'disabled'))->render(),
                'message' => 'Marka Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Marka Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param UpdateBrandRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateBrandRequest $request): JsonResponse
    {
        try {
            $brandUpdateDTO = BrandUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $brand = $this->brandService->updateModel(brandUpdateDTO: $brandUpdateDTO);

            if (empty($brand->id)) {
                throw new CustomException('Marka Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $brandResult = $this->brandContentService->updateOrCreateContent(brand: $brand, languages: $brandUpdateDTO->languages);

            if (empty($brandResult->id)) {
                throw new CustomException('Marka İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Marka Başarıyla Güncelledi.',
                'brand'        => $brand,
                'brandContent' => $brand?->content,
                'image'        => $brand?->getImageHtml(),
                'status'       => $brand?->getStatusInput(),
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
     * @param DeleteBrandRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteBrandRequest $request): JsonResponse
    {
        try {
            $brandDeleteDTO = BrandDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $brand = $this->brandService->deleteModel(brandDeleteDTO: $brandDeleteDTO);

            if (empty($brand->id)) {
                throw new CustomException('Marka Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Marka Başarıyla Silindi.',
                'brand'        => $brand,
                'brandContent' => $brand?->content,
                'image'        => $brand?->getImageHtml(),
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
     * @param IdBrandRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdBrandRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $brand = $this->brandService->getDeletedModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($brand->id)

            ? response()->json([
                'success' => true,
                'brand'   => view('components.backend.brands.update-view', compact('brand', 'disabled'))->render(),
                'message' => 'Silinen Marka Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Silinen Marka Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBrandRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdBrandRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $brand = $this->brandService->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($brand->id)) {
                throw new CustomException('Marka Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Marka Başarıyla Geri Getirildi.',
                'brand'        => $brand,
                'brandContent' => $brand?->content,
                'image'        => $brand?->getImageHtml(),
                'status'       => $brand?->getStatusInput(),
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
     * @param IdBrandRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdBrandRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            $brand = $this->brandService->trashedRemoveModel(id: (int)$valid['id']);

            if (empty($brand)) {
                throw new CustomException('Marka Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Marka Kalıcı Olarak Silindi.',
                'brand'   => $brand,
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
