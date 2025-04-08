<?php

declare(strict_types=1);

namespace App\Services\Backend\Sections;

use App\Interfaces\Sections\SectionContentInterface;
use App\Models\Section;
use App\Models\SectionContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SectionContentService implements SectionContentInterface
{
    /**
     * @param Model $section
     * @param array $languages
     *
     * @return Section|null
     */
    public function updateOrCreateContent(Model $section, array $languages): ?Section
    {
        try {
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                SectionContent::query()->updateOrCreate(
                    [
                        'section_id'  => $section->id,
                        'language_id' => $lang->id,
                    ],
                    [
                        'heading'           => $languages[$lang?->code]['heading'] ?? null,
                        'sub_heading'       => $languages[$lang?->code]['sub_heading'] ?? null,
                        'button_title'      => $languages[$lang?->code]['button_title'] ?? null,
                        'description'       => $languages[$lang?->code]['description'] ?? null,
                        'short_description' => $languages[$lang?->code]['short_description'] ?? null,

                    ]
                );
            }

            $section->load(['content']);

            return $section;
        } catch (\Exception $exception) {
            Log::error("SectionContentService (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}
