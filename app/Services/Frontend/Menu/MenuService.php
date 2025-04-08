<?php

declare(strict_types=1);

namespace App\Services\Frontend\Menu;

use App\Enums\Defaults\StatusEnum;
use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;

class MenuService
{
    /**
     * @return Collection
     */
    public function getActiveMenus(): Collection
    {
        return Page::query()
            ->with(['content', 'subPageMenus.content'])
            ->where('status', StatusEnum::ACTIVE)
            ->orderBy('sorting', 'asc')->get();
    }
}
