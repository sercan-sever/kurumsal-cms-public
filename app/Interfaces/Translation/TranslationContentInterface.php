<?php

declare(strict_types=1);

namespace App\Interfaces\Translation;

use App\DTO\Backend\Translation\TranslationContentCreateDTO;
use App\DTO\Backend\Translation\TranslationContentUpdateDTO;
use App\Models\TranslationContent;

interface TranslationContentInterface
{
    /**
     * @param int $translationId
     * @param string $lang
     *
     * @return bool
     */
    public function importDefaultLangTranslationContent(int $translationId, string $lang = 'en'): bool;


    /**
     * @param TranslationContentCreateDTO $translationContentCreateDTO
     *
     * @return TranslationContent|null
     */
    public function createTranslationContent(TranslationContentCreateDTO $translationContentCreateDTO): ?TranslationContent;


    /**
     * @param int $id
     *
     * @return TranslationContent|null
     */
    public function readTranslationContent(int $id): ?TranslationContent;


    /**
     * @param TranslationContentUpdateDTO $translationContentUpdateDTO
     *
     * @return TranslationContent|null
     */
    public function updateTranslationContent(TranslationContentUpdateDTO $translationContentUpdateDTO): ?TranslationContent;


    /**
     * @param int $id
     *
     * @return TranslationContent|null
     */
    public function deleteTranslationContent(int $id): ?TranslationContent;
}
