<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Translation;

use App\DTO\Backend\Translation\TranslationContentCreateDTO;
use App\DTO\Backend\Translation\TranslationContentUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Translation\CreateTranslationContentRequest;
use App\Http\Requests\Translation\DeleteTranslationContentRequest;
use App\Http\Requests\Translation\ReadTranslationContentRequest;
use App\Http\Requests\Translation\UpdateTranslationContentRequest;
use App\Services\Backend\Translations\TranslationContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TranslationContentController extends Controller
{
    /**
     * @param TranslationContentService $translationContentService
     *
     * @return void
     */
    public function __construct(
        private readonly TranslationContentService $translationContentService
    ) {
        //
    }


    /**
     * @param CreateTranslationContentRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateTranslationContentRequest $request): JsonResponse
    {
        try {
            $translationContentCreateDTO = TranslationContentCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $translationContent = $this->translationContentService->createTranslationContent(translationContentCreateDTO: $translationContentCreateDTO);

            if (empty($translationContent->id)) {
                throw new CustomException('Çeviri Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('translation_' . $translationContent?->translation?->language_id);

            DB::commit();

            return response()->json([
                'success'            => true,
                'message'            => 'Çeviri Başarıyla Eklendi.',
                'translationContent' => $translationContent,
                'isDeletable'        => $translationContent->isDeletable(),
            ], Response::HTTP_CREATED);
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
     * @param ReadTranslationContentRequest $request
     *
     * @return JsonResponse
     */
    public function read(ReadTranslationContentRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $translationContent = $this->translationContentService->readTranslationContent(id: (int)$valid['id']);

        return !empty($translationContent)

            ? response()->json([
                'success'            => true,
                'message'            => 'Çeviri Başarıyla Getirildi.',
                'translationContent' => $translationContent,
                'isDeletable'        => $translationContent->isDeletable(),
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Çeviri Bulunamadı. Daha Sonra Tekrar Deneyiniz !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }



    /**
     * @param ReadTranslationContentRequest $request
     *
     * @return JsonResponse
     */
    public function readView(ReadTranslationContentRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $translationContent = $this->translationContentService->readTranslationContent(id: (int)$valid['id']);

        return !empty($translationContent->id)

            ? response()->json([
                'success'            => true,
                'message'            => 'Çeviri Başarıyla Getirildi.',
                'translationContent' => view('components.backend.translation.update-view', compact('translationContent'))->render(),
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Çeviri Bulunamadı. Daha Sonra Tekrar Deneyiniz !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    /**
     * @param UpdateTranslationContentRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateTranslationContentRequest $request): JsonResponse
    {
        try {
            $translationContentUpdateDTO = TranslationContentUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $translationContent = $this->translationContentService->updateTranslationContent(translationContentUpdateDTO: $translationContentUpdateDTO);

            if (empty($translationContent->id)) {
                throw new CustomException('Çeviri Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('translation_' . $translationContent?->translation?->language_id);

            DB::commit();

            return response()->json([
                'success'            => true,
                'message'            => 'Çeviri Başarıyla Güncellendi.',
                'translationContent' => $translationContent,
                'isDeletable'        => $translationContent->isDeletable(),
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
     * @param DeleteTranslationContentRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteTranslationContentRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $translationContent = $this->translationContentService->deleteTranslationContent(id: (int)$valid['id']);

            if (empty($translationContent->id)) {
                throw new CustomException('Çeviri Silme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('translation_' . $translationContent?->translation?->language_id);

            DB::commit();

            return response()->json([
                'success'            => true,
                'message'            => 'Çeviri Başarıyla Silindi.',
                'translationContent' => $translationContent,
                'isDeletable'        => $translationContent->isDeletable(),
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
