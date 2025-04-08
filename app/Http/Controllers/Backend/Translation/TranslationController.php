<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Translation;

use App\Enums\Permissions\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Translation\CheckStatusTranslationRequest;
use App\Models\Language;
use App\Models\Translation;
use App\Services\Backend\Translations\TranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TranslationController extends Controller
{
    /**
     * @param TranslationService $translationService
     *
     * @return void
     */
    public function __construct(private readonly TranslationService $translationService)
    {
        //
    }


    /**
     * @param Language $language
     *
     * @return View
     */
    public function detail(Language $language): View
    {
        $translation = $this->translationService->readStatusComplatedByLanguageId(languageId: $language->id);

        if (empty($translation)) {
            abort(403, 'ŞU ANDA İŞLEMDE OLABİLİR !!!');
        }

        if (!request()->user()->can(PermissionEnum::STATIC_TEXT_READ->value)) {
            $translation = new Translation();
        }

        return view('backend.modules.translation.translation', compact('translation'));
    }


    /**
     * @param CheckStatusTranslationRequest $request
     *
     * @return JsonResponse
     */
    public function checkStatus(CheckStatusTranslationRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $translation = $this->translationService->readStatusComplatedByLanguageId(languageId: (int)$valid['id']);

        return !empty($translation)

            ? response()->json([
                'success'  => true,
                'message'  => 'Çeviriler Başarıyla Yüklendi.',
                'url'      => route('admin.translation.detail', ['language' => $translation->language_id]),
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Çeviriler Yüklenmemiş Olabilir Daha Sonra Tekrar Deneyiniz !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
