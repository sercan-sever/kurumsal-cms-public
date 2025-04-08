<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\Backend\Service\ServiceImageManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ServiceImageRemove implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param array|null $serviceImages
     *
     * @return void
     */
    public function __construct(public ?array $serviceImages)
    {
        //
    }

    public $tries = 3; // Maksimum 3 kez dene

    public $backoff = [60, 300, 600]; // 1 dk, 5 dk, 10 dk bekle

    /**
     * @param ServiceImageManager $serviceImageManager
     *
     * @return void
     */
    public function handle(ServiceImageManager $serviceImageManager): void
    {
        try {
            $serviceImageManager->allRemoveImage(serviceImages: $this->serviceImages);
        } catch (\Exception $exception) {
            Log::error("ServiceImageRemoveJob (handle) : ", context: [$exception->getMessage()]);
        }
    }
}
