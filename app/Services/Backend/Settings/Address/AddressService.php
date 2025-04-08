<?php

declare(strict_types=1);

namespace App\Services\Backend\Settings\Address;

use App\DTO\Backend\Settings\Address\AddressUpdateDTO;
use App\Interfaces\DTO\BaseDTOInterface;
use App\Interfaces\Settings\Address\AddressInterface;
use App\Models\AddressSetting;
use App\Models\AddressSettingContent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AddressService implements AddressInterface
{
    /**
     * @return AddressSetting|null
     */
    public function getModel(): ?AddressSetting
    {
        return AddressSetting::query()->with('content')->first();
    }

    /**
     * @return AddressSetting|null
     */
    public function getAddressAllContent(): ?AddressSetting
    {
        return AddressSetting::query()->with('allContent')->first();
    }


        /**
     * @param int $id
     *
     * @return AddressSetting|null
     */
    public function getAddressById(int $id): ?AddressSetting
    {
        return AddressSetting::query()->with(['content', 'allContent'])->where('id', $id)->first();
    }


    /**
     * @param AddressUpdateDTO $addressUpdateDTO
     *
     * @return AddressSetting|null
     */
    public function createOrFirst(AddressUpdateDTO $addressUpdateDTO): ?AddressSetting
    {
        try {
            return AddressSetting::query()->updateOrCreate(
                [
                    'setting_id' => $addressUpdateDTO->settingId,
                ],
                [
                    'updated_by' => request()->user()->id,
                    'updated_at' => Carbon::now(),
                ]
            );
        } catch (\Exception $exception) {
            Log::error("AddressService (createGeneralSetting) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param BaseDTOInterface $generalUpdateDTO
     *
     * @return AddressSetting|null
     */
    public function createOrUpdate(BaseDTOInterface $addressUpdateDTO): ?AddressSetting
    {
        try {
            $address = $this->createOrFirst(addressUpdateDTO: $addressUpdateDTO);
            $languages = $addressUpdateDTO->languages;
            $activeLanguages = request('languages', collect());

            if ($activeLanguages->isEmpty()) {
                return null;
            }

            foreach ($activeLanguages as $lang) {
                AddressSettingContent::query()->updateOrCreate(
                    [
                        'address_setting_id' => $address->id,
                        'language_id'        => $lang->id,
                    ],
                    [
                        'email_title_one'     => $languages[$lang?->code]['email_title_one'] ?? null,
                        'email_address_one'   => $languages[$lang?->code]['email_address_one'] ?? null,
                        'email_title_two'     => $languages[$lang?->code]['email_title_two'] ?? null,
                        'email_address_two'   => $languages[$lang?->code]['email_address_two'] ?? null,

                        'phone_title_one'     => $languages[$lang?->code]['phone_title_one'] ?? null,
                        'phone_number_one'    => $languages[$lang?->code]['phone_number_one'] ?? null,
                        'phone_title_two'     => $languages[$lang?->code]['phone_title_two'] ?? null,
                        'phone_number_two'    => $languages[$lang?->code]['phone_number_two'] ?? null,

                        'address_title_one'   => $languages[$lang?->code]['address_title_one'] ?? null,
                        'address_content_one' => $languages[$lang?->code]['address_content_one'] ?? null,
                        'address_iframe_one'  => $languages[$lang?->code]['address_iframe_one'] ?? null,

                        'address_title_two'   => $languages[$lang?->code]['address_title_two'] ?? null,
                        'address_content_two' => $languages[$lang?->code]['address_content_two'] ?? null,
                        'address_iframe_two'  => $languages[$lang?->code]['address_iframe_two'] ?? null,
                    ]
                );
            }

            return $address;
        } catch (\Exception $exception) {
            Log::error("AddressService (createOrUpdates) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}
