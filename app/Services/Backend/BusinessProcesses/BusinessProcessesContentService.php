<?php

declare(strict_types=1);

namespace App\Services\Backend\BusinessProcesses;

use App\Interfaces\BusinessProcesses\BusinessProcessesContentInterface;
use App\Models\BusinessProcesses;
use App\Models\BusinessProcessesContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BusinessProcessesContentService implements BusinessProcessesContentInterface
{
    /**
     * @param Model $businessProcesses
     * @param array $languages
     *
     * @return Service|null
     */
    public function updateOrCreateContent(Model $businessProcesses, array $languages): ?BusinessProcesses
    {
        try {
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                BusinessProcessesContent::query()->updateOrCreate(
                    [
                        'business_processes_id' => $businessProcesses->id,
                        'language_id'           => $lang->id,
                    ],
                    [
                        'header'      => $languages[$lang?->code]['header'] ?? null,
                        'title'       => $languages[$lang?->code]['title'] ?? null,
                        'description' => $languages[$lang?->code]['description'] ?? null,
                    ]
                );
            }

            $businessProcesses->load(['content']);

            return $businessProcesses;
        } catch (\Exception $exception) {
            Log::error("BusinessProcessesContentService (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}
