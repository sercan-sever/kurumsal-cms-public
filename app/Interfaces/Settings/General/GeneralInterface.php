<?php

declare(strict_types=1);

namespace App\Interfaces\Settings\General;

use App\DTO\Backend\Settings\General\GeneralUpdateDTO;
use App\Interfaces\Base\BaseBackendSettingInterface;
use App\Models\GeneralSetting;

interface GeneralInterface extends BaseBackendSettingInterface
{
    /**
     * @return GeneralSetting|null
     */
    public function getGeneralAllContent(): ?GeneralSetting;


    /**
     * @param GeneralUpdateDTO $generalUpdateDTO
     *
     * @return GeneralSetting|null
     */
    public function createOrFirst(GeneralUpdateDTO $generalUpdateDTO): ?GeneralSetting;
}
