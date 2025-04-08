<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\BusinessProcesses;

use App\DTO\Backend\BusinessProcesses\BusinessProcessesCreateDTO;
use App\DTO\Backend\BusinessProcesses\BusinessProcessesDeleteDTO;
use App\DTO\Backend\BusinessProcesses\BusinessProcessesUpdateDTO;

use App\Http\Requests\BusinessProcesses\CreateBusinessProcessesRequest;
use App\Http\Requests\BusinessProcesses\DeleteBusinessProcessesRequest;
use App\Http\Requests\BusinessProcesses\IdBusinessProcessesRequest;
use App\Http\Requests\BusinessProcesses\UpdateBusinessProcessesRequest;

use App\Services\Backend\BusinessProcesses\BusinessProcessesContentService;
use App\Services\Backend\BusinessProcesses\BusinessProcessesService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;

class BusinessProcessesController extends Controller
{
    /**
     * @param BusinessProcessesService $businessProcessesService
     * @param BusinessProcessesContentService $businessProcessesContentService
     *
     * @return void
     */
    public function __construct(
        private readonly BusinessProcessesService $businessProcessesService,
        private readonly BusinessProcessesContentService $businessProcessesContentService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $businessProcesses        = $this->businessProcessesService->getAllModel();
        $deletedBusinessProcesses = $this->businessProcessesService->getAllDeletedModel();

        return view('backend.modules.business-processes.business-processes', compact('businessProcesses', 'deletedBusinessProcesses'));
    }


    /**
     * @param CreateBusinessProcessesRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateBusinessProcessesRequest $request)
    {
        try {
            $businessProcessesCreateDTO = BusinessProcessesCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $businessProcesses = $this->businessProcessesService->createModel(businessProcessesCreateDTO: $businessProcessesCreateDTO);

            if (empty($businessProcesses->id)) {
                throw new CustomException('Süreç Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $businessProcessesContent = $this->businessProcessesContentService->updateOrCreateContent(businessProcesses: $businessProcesses, languages: $businessProcessesCreateDTO->languages);

            if (empty($businessProcessesContent->id)) {
                throw new CustomException('Süreç İçerik Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'           => true,
                'message'           => 'Süreç Başarıyla Eklendi.',
                'businessProcesses' => $businessProcesses,
                'content'           => $businessProcesses?->content,
                'status'            => $businessProcesses?->getStatusInput(),
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
     * @param IdBusinessProcessesRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(IdBusinessProcessesRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $businessProcessesStatus = $this->businessProcessesService->changeStatus(id: (int)$valid['id']);

            if (!$businessProcessesStatus) {
                throw new CustomException('Süreç Durum Değeri Değiştirilemedi !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Süreç Durumu Başarıyla Değiştirildi.',
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
     * @param IdBusinessProcessesRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdBusinessProcessesRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $businessProcesses = $this->businessProcessesService->getModelById(id: (int)$valid['id']);

        return !empty($businessProcesses->id)

            ? response()->json([
                'success'           => true,
                'businessProcesses' => view('components.backend.business-processes.update-view', compact('businessProcesses'))->render(),
                'message'           => 'Süreç Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Süreç Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdBusinessProcessesRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdBusinessProcessesRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $businessProcesses = $this->businessProcessesService->getModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($businessProcesses->id)

            ? response()->json([
                'success'           => true,
                'businessProcesses' => view('components.backend.business-processes.update-view', compact('businessProcesses', 'disabled'))->render(),
                'message'           => 'Süreç Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Süreç Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param UpdateBusinessProcessesRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateBusinessProcessesRequest $request): JsonResponse
    {
        try {
            $businessProcessesUpdateDTO = BusinessProcessesUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $businessProcesses = $this->businessProcessesService->updateModel(businessProcessesUpdateDTO: $businessProcessesUpdateDTO);

            if (empty($businessProcesses->id)) {
                throw new CustomException('Süreç Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $businessProcessesContent = $this->businessProcessesContentService->updateOrCreateContent(businessProcesses: $businessProcesses, languages: $businessProcessesUpdateDTO->languages);

            if (empty($businessProcessesContent->id)) {
                throw new CustomException('Süreç İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'           => true,
                'message'           => 'Süreç Başarıyla Güncellendi.',
                'businessProcesses' => $businessProcesses,
                'content'           => $businessProcesses?->content,
                'status'            => $businessProcesses?->getStatusInput(),
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
     * @param DeleteBusinessProcessesRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteBusinessProcessesRequest $request): JsonResponse
    {
        try {
            $businessProcessesDeleteDTO = BusinessProcessesDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $businessProcesses = $this->businessProcessesService->deleteModel(businessProcessesDeleteDTO: $businessProcessesDeleteDTO);

            if (empty($businessProcesses->id)) {
                throw new CustomException('Süreç Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'           => true,
                'message'           => 'Süreç Başarıyla Silindi.',
                'businessProcesses' => $businessProcesses,
                'content'           => $businessProcesses?->content,
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
     * @param IdBusinessProcessesRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdBusinessProcessesRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $businessProcesses  = $this->businessProcessesService->getDeletedModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($businessProcesses->id)

            ? response()->json([
                'success'           => true,
                'businessProcesses' => view('components.backend.business-processes.update-view', compact('businessProcesses', 'disabled'))->render(),
                'message'           => 'Silinen Süreç Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Silinen Süreci Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }



    /**
     * @param IdBusinessProcessesRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdBusinessProcessesRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $businessProcesses = $this->businessProcessesService->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($businessProcesses->id)) {
                throw new CustomException('Süreç Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'           => true,
                'message'           => 'Süreç Başarıyla Geri Getirildi.',
                'businessProcesses' => $businessProcesses,
                'content'           => $businessProcesses?->content,
                'status'            => $businessProcesses?->getStatusInput(),
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
     * @param IdBusinessProcessesRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdBusinessProcessesRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $businessProcessesId = $this->businessProcessesService->trashedRemoveModel(id: (int)$valid['id']);

            if (empty($businessProcessesId)) {
                throw new \Exception('Süreç Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success'           => true,
                'message'           => 'Süreç Kalıcı Olarak Silindi.',
                'businessProcesses' => $businessProcessesId,
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
