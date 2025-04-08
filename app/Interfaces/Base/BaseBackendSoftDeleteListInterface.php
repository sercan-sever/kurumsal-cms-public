<?php

declare(strict_types=1);

namespace App\Interfaces\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseBackendSoftDeleteListInterface
{
    /**
     * @return Collection
     */
    public function getAllModel(): Collection;

    /**
     * @return Collection
     */
    public function getAllDeletedModel(): Collection;


    /**
     * @param int $id
     *
     * @return Model|null
     */
    public function getModelById(int $id): ?Model;


    /**
     * @param int $id
     *
     * @return Model|null
     */
    public function getDeletedModelById(int $id): ?Model;
}
