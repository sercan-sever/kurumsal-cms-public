<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Language;

use App\Http\Controllers\Controller;
use App\DTO\Backend\Language\LanguageCreateDTO;
use App\DTO\Backend\Language\LanguageDeleteDTO;
use App\DTO\Backend\Language\LanguageUpdateDTO;
use App\Exceptions\CustomException;
use App\Services\Backend\Language\LanguageService;
use App\Http\Requests\Language\ChangeStatusLanguageRequest;
use App\Http\Requests\Language\CreateLanguageRequest;
use App\Http\Requests\Language\DeleteLanguageRequest;
use App\Http\Requests\Language\IdLanguageRequest;
use App\Http\Requests\Language\ReadLanguageRequest;
use App\Http\Requests\Language\TrashedLanguageRequest;
use App\Http\Requests\Language\UpdateLanguageRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LanguageController extends Controller
{
    /**
     * @param LanguageService $languageService
     *
     * @return void
     */
    public function __construct(
        private readonly LanguageService $languageService
    ) {
        //
    }


    /**
     * @return View
     */
    public function index(): View
    {
        $languages = $this->languageService->getAllLanguages();
        $trashedLanguages = $this->languageService->getAllTrashedLanguages();

        return view('backend.modules.language.language', compact('languages', 'trashedLanguages'));
    }


    /**
     * @param IdLanguageRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdLanguageRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $language = $this->languageService->readLanguage(id: (int)$valid['id']);

        return !empty($language->id)

            ? response()->json([
                'success' => true,
                'language' => view('components.backend.language.update-view', compact('language'))->render(),
                'message' => 'Dil Son Güncelleme Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Dil Son Güncelleme Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }


        /**
     * @param IdLanguageRequest $request
     *
     * @return JsonResponse
     */
    public function readDeleteView(IdLanguageRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $language = $this->languageService->readTrashedLanguage(id: (int)$valid['id']);

        return !empty($language->id)

            ? response()->json([
                'success' => true,
                'language' => view('components.backend.language.update-view', compact('language'))->render(),
                'message' => 'Dil Son Güncelleme Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Dil Son Güncelleme Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }



    /**
     * @param CreateLanguageRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateLanguageRequest $request): JsonResponse
    {
        try {
            $createDTO = LanguageCreateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $language = $this->languageService->createModel(createDTO: $createDTO);

            if (empty($language->id)) {
                throw new CustomException('Dil Ekleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('translation_' . $language->id);
            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'Dil Başarıyla Eklendi.',
                'language'      => $language,
                'image'         => $language->getImageHtml(),
                'status'        => $language->getStatusInput(),
                'default'       => $language->getDefaultIcon(),
                'defaultActive' => $language->isActiveDefault(),
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
     * @param ReadLanguageRequest $request
     *
     * @return JsonResponse
     */
    public function read(ReadLanguageRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $language = $this->languageService->readLanguage(id: (int)$valid['id']);

        return !empty($language)

            ? response()->json([
                'success'  => true,
                'message'  => 'Dil Başarıyla Getirildi.',
                'language' => $language,
                'image'    => $language->getImage(),
                'status'   => $language->isActiveStatus(),
                'default'  => $language->isActiveDefault(),
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Dil Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    /**
     * @param ChangeStatusLanguageRequest $request
     *
     * @return JsonResponse
     */
    public function changeStatus(ChangeStatusLanguageRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $changeStatusCheck = $this->languageService->changeStatus(id: (int)$valid['id']);

            if (!$changeStatusCheck) {
                throw new CustomException('Dil Durum Değiştirme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('translation_' . (int)$valid['id']);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Dil Durumu Değiştirildi.',
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
     * @param UpdateLanguageRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateLanguageRequest $request): JsonResponse
    {
        try {
            $updateDTO = LanguageUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $language = $this->languageService->updateModel(updateDTO: $updateDTO);

            if (empty($language->id)) {
                throw new CustomException('Dil Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('translation_' . $language->id);

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => 'Dil Başarıyla Güncellendi.',
                'language'       => $language,
                'image'          => $language->getImageHtml(),
                'status'         => $language->getStatusInput(),
                'default'        => $language->getDefaultIcon(),
                'defaultActive'  => $language->isActiveDefault(),
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
     * @param DeleteLanguageRequest $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteLanguageRequest $request): JsonResponse
    {
        try {
            $languageDeleteDTO = LanguageDeleteDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $language = $this->languageService->deleteModel(languageDeleteDTO: $languageDeleteDTO);

            if (empty($language->id)) {
                throw new CustomException('Dil Silme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('translation_' . $language->id);
            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Dil Başarıyla Silindi.',
                'language' => $language,
                'image'    => $language->getImageHtml(),
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
     * @param TrashedLanguageRequest $request
     *
     * @return JsonResponse
     */
    public function trashedRestore(TrashedLanguageRequest $request): JsonResponse
    {
        try {
            $valid = $request->validated();

            DB::beginTransaction();

            $language = $this->languageService->trashedRestoreModel(id: (int)$valid['id']);

            if (empty($language->id)) {
                throw new CustomException('Dil Geri Yükleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('translation_' . $language->id);
            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Dil Başarıyla Geri Yüklendi.',
                'language' => $language,
                'image'    => $language->getImageHtml(),
                'status'   => $language->getStatusInput(),
                'default'  => $language->getDefaultIcon(),
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
