<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Backend\Translations\TranslationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class BeforeTranslationMiddleware
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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $translations = $this->translationService->readStatusComplatedByLanguageId(languageId: $request?->siteLangID ?? 0000000000);

        // Veritabanından gelen dil verilerini 'validation' grubu altında 'key' ve 'value' ile ekleyin
        foreach ($translations->contents as $translation) {
            // Dinamik olarak çeviriyi geçerli grupta ve anahtar ile ayarlayın
            Lang::addLines([
                $translation->group . '.' . $translation->key => $translation->value
            ], $request?->activeLanguage?->code);
        }

        return $next($request);
    }
}
