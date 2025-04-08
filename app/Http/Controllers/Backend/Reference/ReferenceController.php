<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Reference;

use App\DTO\Backend\Reference\ReferenceCreateDTO;
use App\DTO\Backend\Reference\ReferenceDeleteDTO;
use App\DTO\Backend\Reference\ReferenceUpdateDTO;
use App\Enums\Images\ImageSizeEnum;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reference\CreateReferenceRequest;
use App\Http\Requests\Reference\DeleteReferenceImageRequest;
use App\Http\Requests\Reference\DeleteReferenceRequest;
use App\Http\Requests\Reference\IdReferenceRequest;
use App\Http\Requests\Reference\UpdateReferenceRequest;
use App\Services\Backend\Brand\BrandService;
use App\Services\Backend\Reference\ReferenceContentService;
use App\Services\Backend\Reference\ReferenceImageService;
use App\Services\Backend\Reference\ReferenceService;
use App\Services\Backend\Reference\ReferenceServiceReferenceManage;
use App\Services\Backend\Service\ServiceManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReferenceController extends Controller
{
    /**
     * @param ReferenceService $referenceService
     * @param ReferenceContentService $referenceContentService
     * @param ReferenceImageService $referenceImageService
     * @param ServiceManager $serviceManager
     * @param BrandService $brandService
     *
     * @return void
     */
    public function __construct(
        private readonly ReferenceService $referenceService,
        private readonly ReferenceContentService $referenceContentService,
        private readonly ReferenceImageService $referenceImageService,
        private readonly ReferenceServiceReferenceManage $referenceServiceReferenceManage,
        private readonly ServiceManager $serviceManager,
        private readonly BrandService $brandService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $references        = $this->referenceService->getAllModel();
        $deletedReferences = $this->referenceService->getAllDeletedModel();
        $services          = $this->serviceManager->getAllActiveModel();
        $brands            = $this->brandService->getAllActiveModel();

        return view('backend.modules.reference.reference', compact('references', 'deletedReferences', 'services', 'brands'));
    }


    /**
     * @param CreateReferenceRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateReferenceRequest $request)
    {
        try {
            $otherErrorTitle = '';

            $referenceCreateDTO = ReferenceCreateDTO::fromRequest(request: $request);

            $fileResult = $this->getFileSize(referenceImages: $referenceCreateDTO->referenceImages);

            if ($fileResult['totalSize'] > $fileResult['maxTotalSize']) {
                throw new CustomException('Seçilen Referans Görsellerinin Toplam Boyutu En Fazla ' . ImageSizeEnum::SIZE_3->value . ' Olabilir.');
            }

            DB::beginTransaction();

            $reference = $this->referenceService->createModel(referenceCreateDTO: $referenceCreateDTO);

            if (empty($reference->id)) {
                throw new CustomException('Referans Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $referenceContentResult = $this->referenceContentService->updateOrCreateContent(reference: $reference, languages: $referenceCreateDTO->languages);

            if (empty($referenceContentResult->id)) {
                throw new CustomException('Referans İçerik Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $referenceServiceReferencResult = $this->referenceServiceReferenceManage->updateOrCreateContent(reference: $reference, services: $referenceCreateDTO->services);

            if (empty($referenceServiceReferencResult->id)) {
                throw new CustomException('Referans Hizmetleri Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $referenceImageResult = $this->referenceImageService->createModelImage(reference: $reference, referenceImages: $referenceCreateDTO->referenceImages);

            if (empty($referenceImageResult->id)) {
                $otherErrorTitle = ' ( Referans Görselleri Eklenemedi !!! )';
            }

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Referans Başarıyla Eklendi.' . $otherErrorTitle,
                'reference' => $reference,
                'title'     => $reference?->content?->title,
                'image'     => $reference->getOtherImageHtml(),
                'status'    => $reference->getStatusInput(),
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
     * @param IdReferenceRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(IdReferenceRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $referenceStatus = $this->referenceService->changeStatus(id: (int)$valid['id']);

            if (!$referenceStatus) {
                throw new CustomException('Referans Durum Değeri Değiştirilemedi !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Referans Durumu Başarıyla Değiştirildi.',
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
     * @param IdReferenceRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdReferenceRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $reference = $this->referenceService->getModelById(id: (int)$valid['id']);
        $services  = $this->serviceManager->getAllActiveModel();
        $brands    = $this->brandService->getAllActiveModel();

        return !empty($reference->id)

            ? response()->json([
                'success'   => true,
                'reference' => view('components.backend.reference.update-view', compact('reference', 'services', 'brands'))->render(),
                'message'   => 'Referans Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Referans Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdReferenceRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdReferenceRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $reference = $this->referenceService->getModelById(id: (int)$valid['id']);
        $services  = $this->serviceManager->getAllActiveModel();
        $brands    = $this->brandService->getAllActiveModel();
        $disabled  = true;

        return !empty($reference->id)

            ? response()->json([
                'success'   => true,
                'reference' => view('components.backend.reference.update-view', compact('reference', 'services', 'brands', 'disabled'))->render(),
                'message'   => 'Referans Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Referans Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param UpdateReferenceRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateReferenceRequest $request): JsonResponse
    {
        try {
            $otherErrorTitle = '';

            $referenceUpdateDTO = ReferenceUpdateDTO::fromRequest(request: $request);

            $fileResult = $this->getFileSize(referenceImages: $referenceUpdateDTO->referenceImages);

            if ($fileResult['totalSize'] > $fileResult['maxTotalSize']) {
                throw new CustomException('Seçilen Referans Görsellerinin Toplam Boyutu En Fazla ' . ImageSizeEnum::SIZE_3->value . ' olabilir.');
            }

            DB::beginTransaction();

            $reference = $this->referenceService->updateModel(referenceUpdateDTO: $referenceUpdateDTO);

            if (empty($reference->id)) {
                throw new CustomException('Referans Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $referenceContent = $this->referenceContentService->updateOrCreateContent(reference: $reference, languages: $referenceUpdateDTO->languages);

            if (empty($referenceContent->id)) {
                throw new CustomException('Referans İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $referenceServiceReferencResult = $this->referenceServiceReferenceManage->updateOrCreateContent(reference: $reference, services: $referenceUpdateDTO->services);

            if (empty($referenceServiceReferencResult->id)) {
                throw new CustomException('Referans Hizmetleri Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $referenceImageResult = $this->referenceImageService->createModelImage(reference: $reference, referenceImages: $referenceUpdateDTO->referenceImages);

            if (empty($referenceImageResult->id)) {
                $otherErrorTitle = ' ( Referans Görselleri Eklenemedi !!! )';
            }

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Referans Başarıyla Güncellendi.' . $otherErrorTitle,
                'reference' => $reference,
                'title'     => $reference?->content?->title,
                'image'     => $reference->getOtherImageHtml(),
                'status'    => $reference->getStatusInput(),
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
     * @param DeleteReferenceRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteReferenceRequest $request): JsonResponse
    {
        try {
            $referenceDeleteDTO = ReferenceDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $reference = $this->referenceService->deleteModel(referenceDeleteDTO: $referenceDeleteDTO);

            if (empty($reference->id)) {
                throw new CustomException('Referans Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Referans Başarıyla Silindi.',
                'reference' => $reference,
                'title'     => $reference?->content?->title,
                'image'     => $reference->getOtherImageHtml(),
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
     * @param DeleteReferenceImageRequest $request
     *
     * @return JsonResponse
     */
    public function deleteImage(DeleteReferenceImageRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $reference = $this->referenceImageService->deleteImage(id: $valid['id'], referenceId: $valid['reference_id']);

            if (empty($reference)) {
                throw new CustomException('Referans Görseli Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Referans Görseli Silindi.',
                'reference' => $reference,
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
     * @param IdReferenceRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdReferenceRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $reference  = $this->referenceService->getDeletedModelById(id: (int)$valid['id']);
        $services   = $this->serviceManager->getAllActiveModel();
        $brands     = $this->brandService->getAllActiveModel();
        $disabled   = true;

        return !empty($reference->id)

            ? response()->json([
                'success'   => true,
                'reference' => view('components.backend.reference.update-view', compact('reference', 'services', 'brands', 'disabled'))->render(),
                'message'   => 'Silinen Referans Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Silinen Referansi Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdReferenceRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdReferenceRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $reference = $this->referenceService->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($reference->id)) {
                throw new CustomException('Referansi Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Referans Başarıyla Geri Getirildi.',
                'reference' => $reference,
                'title'     => $reference?->content?->title,
                'image'     => $reference->getOtherImageHtml(),
                'status'    => $reference->getStatusInput(),
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
     * @param IdReferenceRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdReferenceRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $referenceId = $this->referenceService->trashedRemoveModel(id: (int)$valid['id']);

            if (empty($referenceId)) {
                throw new \Exception('Referans Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Referans Kalıcı Olarak Silindi.',
                'reference' => $referenceId,
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
     * @param array|null $referenceImages
     *
     * @return array
     */
    private function getFileSize(?array $referenceImages): array
    {
        $totalSize = 0;

        if (!empty($referenceImages)) {
            foreach ($referenceImages as $file) {
                $totalSize += $file->getSize();
            }

            $maxTotalSize = ImageSizeEnum::SIZE_3->value * 1024 * 1024; // Maksimum toplam 10MB

            return ['totalSize' => $totalSize, 'maxTotalSize' => $maxTotalSize];
        }

        return ['totalSize' => 0, 'maxTotalSize' => 1];
    }
}
