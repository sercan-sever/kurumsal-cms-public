<?php

namespace App\Interfaces\Pages;

use App\Interfaces\Base\BaseBackendInterface;
use App\Interfaces\Base\BaseBackendSoftDeleteListInterface;
use Illuminate\Database\Eloquent\Collection;

interface PageInterface  extends BaseBackendInterface, BaseBackendSoftDeleteListInterface
{
    /**
     * @return Collection
     */
    public function getAllTopMenuModel(): Collection;


    /**
     * @return int|null
     */
    public function getMaxSorting(): ?int;


    /**
     * @return int
     */
    public function getActiveModelCount(): int;


    /**
     * @return int
     */
    public function getPassiveModelCount(): int;


    /**
     * @return int
     */
    public function getAllDeletedModelCount(): int;


    /**
     * @return Collection
     */
    public function getAllActiveMenuFrontend(): Collection;
}
