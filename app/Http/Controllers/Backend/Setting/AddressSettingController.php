<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend\Setting;

use App\DTO\Backend\Settings\Address\AddressUpdateDTO;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Address\IdAddressRequest;
use App\Http\Requests\Settings\Address\UpdateAddressRequest;
use App\Services\Backend\Settings\Address\AddressService;
use App\Services\Backend\Settings\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AddressSettingController extends Controller
{
    /**
     * @param SettingService $settingService
     * @param AddressService $addressService
     *
     * @return void
     */
    public function __construct(
        private readonly SettingService $settingService,
        private readonly AddressService $addressService,
    ) {
        //
    }


    /**
     * @return View
     */
    public function address(): View
    {
        $setting = $this->settingService->getSetting();
        $address = $this->addressService->getAddressAllContent();

        return view('backend.modules.settings.address-setting', compact('setting', 'address'));
    }


    /**
     * @param UpdateAddressRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateAddressRequest $request)
    {
        try {
            $addressUpdateDTO = AddressUpdateDTO::fromRequest(request: $request);

            DB::beginTransaction();

            $address = $this->addressService->createOrUpdate(addressUpdateDTO: $addressUpdateDTO);

            if (empty($address->id)) {
                throw new CustomException('Adres Güncelleme İşleminde Bir Sorun Oluştu !!!');
            }

            Cache::forget('setting');

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Adres Başarıyla Güncellendi.',
                'address'  => $address,
            ], Response::HTTP_OK);
        } catch (CustomException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Bir Hata Meydana Geldi. Lütfen Daha Sonra Tekrar Deneyin.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param IdAddressRequest $request
     *
     * @return JsonResponse
     */
    public function readView(IdAddressRequest $request): JsonResponse
    {
        $valid = $request->validated();

        $address = $this->addressService->getAddressById(id: (int)$valid['id']);

        return !empty($address->id)

            ? response()->json([
                'success' => true,
                'title'   => $address?->updatedBy?->name,
                'email'   => $address?->updatedBy?->email,
                'statu'   => $address?->updatedBy?->getRoleHtml(),
                'date'    => $address?->getUpdatedAt(),
                'message' => 'Adres Ayarları Son Güncelleme Başarıyla Getirildi.',
            ], Response::HTTP_OK)

            : response()->json([
                'success' => false,
                'message' => 'Adres Ayarları Son Güncelleme Getirme İşleminde Bir Sorun Oluştu !!!'
            ], Response::HTTP_UNAUTHORIZED);
    }
}
