<?php

declare(strict_types=1);

namespace App\Interfaces\Base;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface BaseBackendInterface
{
    /**
     * @param BaseDTOInterface $baseDTOInterface
     *
     * @return Model|null
     */
    public function createModel(BaseDTOInterface $baseDTOInterface): ?Model;


    /**
     * @param int $id
     *
     * @return bool
     */
    public function changeStatus(int $id): bool;


    /**
     * @param BaseDTOInterface $baseDTOInterface
     *
     * @return Model|null
     */
    public function updateModel(BaseDTOInterface $baseDTOInterface): ?Model;


    /**
     * @param BaseDTOInterface $baseDTOInterface
     *
     * @return Model|null
     */
    public function deleteModel(BaseDTOInterface $baseDTOInterface): ?Model;


    /**
     * @param int $id
     *
     * @return Model|null
     */
    public function trashedRestoreModel(int $id): ?Model;


    /**
     * @param int $id
     *
     * @return int|null
     */
    public function trashedRemoveModel(int $id): ?int;
}
