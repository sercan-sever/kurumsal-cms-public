<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\Backend\Reference\ReferenceImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReferenceImageRemove implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param array|null $referenceImages
     *
     * @return void
     */
    public function __construct(public ?array $referenceImages)
    {
        //
    }

    public $tries = 3; // Maksimum 3 kez dene

    public $backoff = [60, 300, 600]; // 1 dk, 5 dk, 10 dk bekle


    /**
     * @param ReferenceImageService $referenceImageService
     *
     * @return void
     */
    public function handle(ReferenceImageService $referenceImageService): void
    {
        try {
            $referenceImageService->allRemoveImage(referenceImages: $this->referenceImages);
        } catch (\Exception $exception) {
            Log::error("ReferenceImageRemoveJob (handle) : ", context: [$exception->getMessage()]);
        }
    }
}
