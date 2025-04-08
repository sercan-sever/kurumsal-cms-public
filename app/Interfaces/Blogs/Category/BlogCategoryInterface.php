<?php

declare(strict_types=1);

namespace App\Interfaces\Blogs\Category;

use App\Interfaces\Base\BaseBackendInterface;
use App\Interfaces\Base\BaseBackendSoftDeleteListInterface;
use Illuminate\Database\Eloquent\Collection;

interface BlogCategoryInterface extends BaseBackendInterface, BaseBackendSoftDeleteListInterface
{
    /**
     * @return Collection
     */
    public function getAllActiveBlogCategory(): Collection;
}
