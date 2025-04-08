<?php

namespace App\Interfaces\Brand;

use App\Interfaces\Base\BaseBackendInterface;
use App\Interfaces\Base\BaseBackendSoftDeleteListInterface;
use Illuminate\Database\Eloquent\Collection;

interface BrandInterface extends BaseBackendInterface, BaseBackendSoftDeleteListInterface
{
    /**
     * @return Collection
     */
    public function getAllActiveModel(): Collection;
}
