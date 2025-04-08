<?php

declare(strict_types=1);

namespace App\Interfaces\Settings\Address;

use App\DTO\Backend\Settings\Address\AddressUpdateDTO;
use App\Interfaces\Base\BaseBackendSettingInterface;
use App\Models\AddressSetting;

interface AddressInterface extends BaseBackendSettingInterface
{
    /**
     * @return AddressSetting|null
     */
    public function getAddressAllContent(): ?AddressSetting;

    /**
     * @param AddressUpdateDTO $addressUpdateDTO
     *
     * @return AddressSetting|null
     */
    public function createOrFirst(AddressUpdateDTO $addressUpdateDTO): ?AddressSetting;
}
