<?php

declare(strict_types=1);

namespace App\Services\Frontend\References;

use App\Enums\Defaults\StatusEnum;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Collection;

class ReferenceService
{
    /**
     * @return Collection
     */
    public function getAllModel(): Collection
    {
        return Reference::query()
            ->with(['content', 'allContent'])
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }


    /**
     * @return string $slug
     *
     * @return Reference|null
     */
    public function getReferenceDetail(string $slug): ?Reference
    {
        return Reference::query()
            ->with(['allImage', 'services', 'brand'])
            ->where('status', StatusEnum::ACTIVE)
            ->whereHas('content', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->first();
    }
}
