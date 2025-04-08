<?php

declare(strict_types=1);

namespace App\Services\Backend\Service;

use App\Interfaces\Service\ServiceContentInterface;
use App\Models\Service;
use App\Models\ServiceContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ServiceContentManager implements ServiceContentInterface
{
    /**
     * @param Model $service
     * @param array $languages
     *
     * @return Service|null
     */
    public function updateOrCreateContent(Model $service, array $languages): ?Service
    {
        try {
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                ServiceContent::query()->updateOrCreate(
                    [
                        'service_id'    => $service->id,
                        'language_id'  => $lang->id,
                    ],
                    [
                        'title'             => $languages[$lang?->code]['title'] ?? null,
                        'slug'              => str($languages[$lang?->code]['title'] ?? '')->slug(),
                        'short_description' => $languages[$lang?->code]['short_description'] ?? null,
                        'description'       => $languages[$lang?->code]['description'] ?? null,
                        'meta_keywords'     => $languages[$lang?->code]['meta_keywords'] ?? null,
                        'meta_descriptions' => $languages[$lang?->code]['meta_descriptions'] ?? null,
                    ]
                );
            }

            $service->load(['content']);

            return $service;
        } catch (\Exception $exception) {
            Log::error("ServiceContentManager (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}
