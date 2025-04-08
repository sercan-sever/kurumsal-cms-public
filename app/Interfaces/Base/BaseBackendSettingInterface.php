<?php

declare(strict_types=1);

namespace App\Interfaces\Base;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface BaseBackendSettingInterface
{
    /**
     * @return Model|null
     */
    public function getModel(): ?Model;


    /**
     * @param BaseDTOInterface $updateDTO
     *
     * @return Model|null
     */
    public function createOrUpdate(BaseDTOInterface $updateDTO): ?Model;
}
