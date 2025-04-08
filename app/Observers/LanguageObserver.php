<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Language;
use App\Services\Backend\Translations\TranslationService;

class LanguageObserver
{
    /**
     * @param TranslationService $translationService
     *
     * @return void
     */
    public function __construct(private readonly TranslationService $translationService) {}


    /**
     * @param Language $language
     *
     * @return void
     */
    public function created(Language $language): void
    {
        // Translate İçin Tablo Oluşturuluyor
        $this->translationService->createTranslation(languageId: $language->id);
    }
}
