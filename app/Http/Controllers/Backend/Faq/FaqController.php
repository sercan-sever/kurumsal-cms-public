<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Faq;

use App\DTO\Backend\Faq\FaqCreateDTO;
use App\DTO\Backend\Faq\FaqDeleteDTO;
use App\DTO\Backend\Faq\FaqUpdateDTO;
use App\Http\Controllers\Controller;
use App\Services\Backend\Faq\FaqContentService;
use App\Services\Backend\Faq\FaqService;

use App\Exceptions\CustomException;
use App\Http\Requests\Faq\CreateFaqRequest;
use App\Http\Requests\Faq\DeleteFaqRequest;
use App\Http\Requests\Faq\IdFaqRequest;
use App\Http\Requests\Faq\UpdateFaqRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class FaqController extends Controller
{
    /**
     * @param FaqService $faqService
     * @param FaqContentService $faqContentService
     *
     * @return void
     */
    public function __construct(
        private readonly FaqService $faqService,
        private readonly FaqContentService $faqContentService,
    ) {
        //
    }



    /**
     * @return View
     */
    public function index(): View
    {
        $faqs = $this->faqService->getAllModel();
        $deletedFaqs = $this->faqService->getAllDeletedModel();

        return view('backend.modules.faq.faq', compact('faqs', 'deletedFaqs'));
    }


    /**
     * @param CreateFaqRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateFaqRequest $request): JsonResponse
    {
        try {
            $faqCreateDTO = FaqCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $faq = $this->faqService->createModel(faqCreateDTO: $faqCreateDTO);

            if (empty($faq->id)) {
                throw new CustomException('S. S. S. Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            $faqResult = $this->faqContentService->updateOrCreateContent(faq: $faq, languages: $faqCreateDTO->languages);

            if (empty($faqResult->id)) {
                throw new CustomException('S. S. S. Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'S. S. S. Başarıyla Eklendi.',
                'faq'     => $faq,
                'title'   => $faq?->content?->title,
                'status'  => $faq?->getStatusInput(),
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
     * @param IdFaqRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(IdFaqRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $faqStatus = $this->faqService->changeStatus(id: (int)$valid['id']);

            if (!$faqStatus) {
                throw new CustomException('S. S. S. Durum Değiştirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'S. S. S. Durumu Başarıyla Değiştirildi.',
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
     * @param IdFaqRequest $request
     *
     * @return JsonResponse
     */
    public function read(IdFaqRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $faq = $this->faqService->getModelById(id: (int)$valid['id']);

        return !empty($faq->id)

            ? response()->json([
                'success' => true,
                'faq'  => view('components.backend.faqs.update-view', compact('faq'))->render(),
                'message' => 'S. S. S. Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'S. S. S. Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param IdFaqRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdFaqRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $faq      = $this->faqService->getModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($faq->id)

            ? response()->json([
                'success' => true,
                'faq'  => view('components.backend.faqs.update-view', compact('faq', 'disabled'))->render(),
                'message' => 'S. S. S. Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'S. S. S. Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param UpdateFaqRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateFaqRequest $request): JsonResponse
    {
        try {
            $faqUpdateDTO = FaqUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $faq = $this->faqService->updateModel(faqUpdateDTO: $faqUpdateDTO);

            if (empty($faq->id)) {
                throw new CustomException('S. S. S. Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            $faqResult = $this->faqContentService->updateOrCreateContent(faq: $faq, languages: $faqUpdateDTO->languages);

            if (empty($faqResult->id)) {
                throw new CustomException('S. S. S. İçerik Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'S. S. S. Başarıyla Güncelledi.',
                'faq'     => $faq,
                'title'   => $faq?->content?->title,
                'status'  => $faq->getStatusInput(),
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error("FaqContentController (update) : ", context: [$exception->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * @param DeleteFaqRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteFaqRequest $request): JsonResponse
    {
        try {
            $faqDeleteDTO = FaqDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $faq = $this->faqService->deleteModel(faqDeleteDTO: $faqDeleteDTO);

            if (empty($faq->id)) {
                throw new CustomException('S. S. S. Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'S. S. S. Başarıyla Silindi.',
                'faq'     => $faq,
                'title'   => $faq?->content?->title,
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
     * @param IdFaqRequest $request
     *
     * @return JsonResponse
     */
    public function readTrashed(IdFaqRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $faq = $this->faqService->getDeletedModelById(id: (int)$valid['id']);
        $disabled = true;

        return !empty($faq->id)

            ? response()->json([
                'success' => true,
                'faq'     => view('components.backend.faqs.update-view', compact('faq', 'disabled'))->render(),
                'message' => 'Silinen S. S. S. Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Silinen S. S. S. Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param IdFaqRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(IdFaqRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $faq = $this->faqService->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($faq->id)) {
                throw new CustomException('S. S. S. Geri Getirme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'S. S. S. Başarıyla Geri Getirildi.',
                'faq'     => $faq,
                'title'   => $faq?->content?->title,
                'status'  => $faq->getStatusInput(),
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
     * @param IdFaqRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRemove(IdFaqRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            $faq = $this->faqService->trashedRemoveModel(id: (int)$valid['id']);

            if (empty($faq)) {
                throw new CustomException('S. S. S. Kalıcı Silme İşleminde Bir Sorun Oluştu !!!');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'S. S. S. Kalıcı Olarak Silindi.',
                'faq'     => $faq,
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
