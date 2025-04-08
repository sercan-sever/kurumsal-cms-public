<?php

declare(strict_types=1);

namespace App\Interfaces\Translation;

use App\Models\Translation;

interface TranslationInterface
{
    /**
     * @param int $languageId
     *
     * @return Translation|null
     */
    public function createTranslation(int $languageId): ?Translation;


    /**
     * @param int $translationId
     * @param string $status
     *
     * @return bool
     */
    public function changeStatusTranslation(int $translationId, string $status): bool;


    /**
     * @param int $languageId
     *
     * @return Translation|null
     */
    public function readStatusComplatedByLanguageId(int $languageId): ?Translation;
}
