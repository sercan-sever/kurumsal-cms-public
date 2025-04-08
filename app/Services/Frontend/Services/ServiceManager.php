<?php

declare(strict_types=1);

namespace App\Services\Frontend\Services;

use App\Enums\Defaults\StatusEnum;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

class ServiceManager
{
    /**
     * @return Collection
     */
    public function getServices(): Collection
    {
        return Service::query()
            ->with(['content', 'allContent'])
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }

    /**
     * @return string $slug
     *
     * @return Service|null
     */
    public function getServiceDetail(string $slug): ?Service
    {
        return Service::query()
            ->with('allImage')
            ->where('status', StatusEnum::ACTIVE)
            ->whereHas('content', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->first();
    }
}
