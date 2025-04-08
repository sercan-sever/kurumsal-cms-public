<?php

declare(strict_types=1);

namespace App\Interfaces\Service;

use App\Interfaces\Base\BaseBackendInterface;
use App\Interfaces\Base\BaseBackendSoftDeleteListInterface;
use Illuminate\Database\Eloquent\Collection;

interface ServiceInterface extends BaseBackendInterface, BaseBackendSoftDeleteListInterface
{
    /**
     * @return Collection
     */
    public function getAllActiveModel(): Collection;
}
