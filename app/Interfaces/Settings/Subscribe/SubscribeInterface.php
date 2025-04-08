<?php

declare(strict_types=1);

namespace App\Interfaces\Settings\Subscribe;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface SubscribeInterface
{
    /**
     * @return Model|null
     */
    public function getModel(): ?Model;


    /**
     * @param BaseDTOInterface $updateDTO
     * @param int $settingId
     *
     * @return Model|null
     */
    public function createOrUpdate(BaseDTOInterface $updateDTO, int $settingId): ?Model;
}
