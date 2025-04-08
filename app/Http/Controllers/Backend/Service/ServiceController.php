<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Service;

use App\DTO\Backend\Service\ServiceCreateDTO;
use App\DTO\Backend\Service\ServiceDeleteDTO;
use App\DTO\Backend\Service\ServiceUpdateDTO;
use App\Enums\Images\ImageSizeEnum;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Service\CreateServiceRequest;
use App\Http\Requests\Service\DeleteServiceImageRequest;
use App\Http\Requests\Service\DeleteServiceRequest;
use App\Http\Requests\Service\IdServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Services\Backend\Service\ServiceContentManager;
use App\Services\Backend\Service\ServiceImageManager;
use App\Services\Backend\Service\ServiceManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * @return void
     */
    public function __construct(
        private readonly ServiceManager $serviceManager,
        private readonly ServiceContentManager $serviceContentManager,
        private readonly ServiceImageManager $serviceImageManager,
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $services        = $this->serviceManager->getAllModel();
        $deletedServices = $this->serviceManager->getAllDeletedModel();

        return view('backend.modules.service.service', compact('services', 'deletedServices'));
    }


    /**
     * @param CreateServiceRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateServiceRequest $request)
    {
        try {
            $otherErrorTitle = '';

            $serviceCreateDTO = ServiceCreateDTO::fromRequest(request: $request);

            $fileResult = $this->getFileSize(serviceImages: $serviceCreateDTO->serviceImages);

            if ($fileResult['totalSize'] > $fileResult['maxTotalSize']) {
                throw new CustomException('Seçilen Hizmet Görsellerinin Toplam Boyutu En Fazla ' . ImageSizeEnum::SIZE_3->value . ' Olabilir.');
            }

            DB::beginTransaction();

            $service = $this->serviceManager->createModel(serviceCreateDTO: $serviceCreateDTO);

            if (empty($service->id)) {
                throw new CustomException('Hizmet Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $serviceContentResult = $this->serviceContentManager->updateOrCreateContent(service: $service, languages: $serviceCreateDTO->languages);

            if (empty($serviceContentResult->id)) {
                throw new CustomException('Hizmet İçerik Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $serviceImageResult = $this->serviceImageManager->createModelImage(service: $service, serviceImages: $serviceCreateDTO->serviceImages);

            if (empty($serviceImageResult->id)) {
                $otherErrorTitle = ' ( Hizmet Görselleri Eklenemedi !!! )';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hizmet Başarıyla Eklendi.' . $otherErrorTitle,
                'service' => $service,
                'title'   => $service?->content?->title,
                'image'   => $service->getOtherImageHtml(),
                'status'  => $service->getStatusInput(),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error("ServiceController (create) : ", context: [$exception->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param IdServiceRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(IdServiceRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $serviceStatus = $this->serviceManager->changeStatus(id: (int)$valid['id']);

            if (!$serviceStatus) {
                throw new CustomException('Hizmet Durum Değeri Değiştirilemedi !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hizmet Durumu Başarıyla Değiştirildi.',
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
     * @param IdServiceRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdServiceRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $service = $this->serviceManager->getModelById(id: (int)$valid['id']);

        return !empty($service->id)

            ? response()->json([
                'success' => true,
                'service' => view('components.backend.service.update-view', compact('service'))->render(),
                'message' => 'Hizmet Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Hizmet Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param IdServiceRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdServiceRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $service = $this->serviceManager->getModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($service->id)

            ? response()->json([
                'success' => true,
                'service' => view('components.backend.service.update-view', compact('service', 'disabled'))->render(),
                'message' => 'Hizmet Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Hizmet Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param UpdateServiceRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateServiceRequest $request): JsonResponse
    {
        try {
            $otherErrorTitle = '';

            $serviceUpdateDTO = ServiceUpdateDTO::fromRequest(request: $request);

            $fileResult = $this->getFileSize(serviceImages: $serviceUpdateDTO->serviceImages);

            if ($fileResult['totalSize'] > $fileResult['maxTotalSize']) {
                throw new CustomException('Seçilen Hizmet Görsellerinin Toplam Boyutu En Fazla ' . ImageSizeEnum::SIZE_3->value . ' olabilir.');
            }

            DB::beginTransaction();

            $service = $this->serviceManager->updateModel(serviceUpdateDTO: $serviceUpdateDTO);

            if (empty($service->id)) {
                throw new CustomException('Hizmet Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $serviceContent = $this->serviceContentManager->updateOrCreateContent(service: $service, languages: $serviceUpdateDTO->languages);

            if (empty($serviceContent->id)) {
                throw new CustomException('Hizmet İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $serviceImageResult = $this->serviceImageManager->createModelImage(service: $service, serviceImages: $serviceUpdateDTO->serviceImages);

            if (empty($serviceImageResult->id)) {
                $otherErrorTitle = ' ( Hizmet Görselleri Eklenemedi !!! )';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hizmet Başarıyla Güncellendi.' . $otherErrorTitle,
                'service' => $service,
                'title'   => $service?->content?->title,
                'image'   => $service->getOtherImageHtml(),
                'status'  => $service->getStatusInput(),
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
     * @param DeleteServiceRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteServiceRequest $request): JsonResponse
    {
        try {
            $serviceDeleteDTO = ServiceDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $service = $this->serviceManager->deleteModel(serviceDeleteDTO: $serviceDeleteDTO);

            if (empty($service->id)) {
                throw new CustomException('Hizmet Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hizmet Başarıyla Silindi.',
                'service' => $service,
                'title'   => $service?->content?->title,
                'image'   => $service->getOtherImageHtml(),
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
     * @param DeleteServiceImageRequest $request
     *
     * @return JsonResponse
     */
    public function deleteImage(DeleteServiceImageRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $service = $this->serviceImageManager->deleteImage(id: $valid['id'], serviceId: $valid['service_id']);

            if (empty($service)) {
                throw new CustomException('Hizmet Görseli Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hizmet Görseli Silindi.',
                'service' => $service,
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error("deleteImage : ", context: [$exception->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param IdServiceRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdServiceRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $service  = $this->serviceManager->getDeletedModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($service->id)

            ? response()->json([
                'success' => true,
                'service' => view('components.backend.service.update-view', compact('service', 'disabled'))->render(),
                'message' => 'Silinen Hizmet Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Silinen Hizmeti Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdServiceRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdServiceRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $service = $this->serviceManager->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($service->id)) {
                throw new CustomException('Hizmeti Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hizmet Başarıyla Geri Getirildi.',
                'service' => $service,
                'title'   => $service?->content?->title,
                'image'   => $service->getOtherImageHtml(),
                'status'  => $service->getStatusInput(),
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
     * @param IdServiceRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdServiceRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $serviceId = $this->serviceManager->trashedRemoveModel(id: (int)$valid['id']);

            if (empty($serviceId)) {
                throw new \Exception('Hizmet Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hizmet Kalıcı Olarak Silindi.',
                'service' => $serviceId,
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
     * @param array|null $serviceImages
     *
     * @return array
     */
    private function getFileSize(?array $serviceImages): array
    {
        $totalSize = 0;

        if (!empty($serviceImages)) {
            foreach ($serviceImages as $file) {
                $totalSize += $file->getSize();
            }

            $maxTotalSize = ImageSizeEnum::SIZE_3->value * 1024 * 1024; // Maksimum toplam 10MB

            return ['totalSize' => $totalSize, 'maxTotalSize' => $maxTotalSize];
        }

        return ['totalSize' => 0, 'maxTotalSize' => 1];
    }
}
