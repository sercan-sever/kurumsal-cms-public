<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\Jobs\JobStatusEnum;
use App\Services\Backend\Translations\TranslationContentService;
use App\Services\Backend\Translations\TranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslationImportDefaultLang implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param int $translationId
     * @param string $locale
     *
     * @return void
     */
    public function __construct(
        private readonly int $translationId,
        private readonly string $locale
    ) {
        //
    }

    public $tries = 3; // Maksimum 3 kez dene

    public $backoff = [60, 300, 600]; // 1 dk, 5 dk, 10 dk bekle

    /**
     * @param TranslationService $translationService
     *
     * @return void
     */
    public function handle(
        TranslationService $translationService,
        TranslationContentService $translationContentService,
        ): void
    {
        try {
            $result =  $translationContentService->importDefaultLangTranslationContent(
                translationId: $this->translationId,
                lang: $this->locale,
            );

            $translationService->changeStatusTranslation(
                translationId: $this->translationId,
                status: $result ? JobStatusEnum::COMPLETED->value : JobStatusEnum::FAILED->value,
            );
        } catch (\Exception $exception) {
            Log::error("TranslationImportDefaultLang (handle) : ", context: [$exception->getMessage()]);

            $translationService->changeStatusTranslation(
                translationId: $this->translationId,
                status: JobStatusEnum::FAILED->value
            );
        }
    }
}
