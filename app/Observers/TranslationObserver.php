<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\TranslationImportDefaultLang;
use App\Models\Translation;

class TranslationObserver
{
    /**
     * @param Translation $translation
     *
     * @return void
     */
    public function created(Translation $translation): void
    {
        // Kuyruğa Ekliyoruz Dil Ekleme Başarılı İse
        TranslationImportDefaultLang::dispatch($translation->id, 'tr');
    }
}
