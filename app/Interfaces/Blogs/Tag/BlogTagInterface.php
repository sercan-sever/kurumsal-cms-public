<?php

declare(strict_types=1);

namespace App\Interfaces\Blogs\Tag;

use App\Interfaces\Base\BaseBackendInterface;
use App\Interfaces\Base\BaseBackendSoftDeleteListInterface;
use Illuminate\Database\Eloquent\Collection;

interface BlogTagInterface extends BaseBackendInterface, BaseBackendSoftDeleteListInterface
{
    /**
     * @return Collection
     */
    public function getAllActiveBlogTag(): Collection;
}
