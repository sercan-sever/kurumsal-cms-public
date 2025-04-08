<?php

declare(strict_types=1);

namespace App\Services\Backend\Settings\Logo;

use App\DTO\Backend\Settings\Logo\LogoUpdateDTO;
use App\Interfaces\Settings\Logo\LogoInterface;
use App\Models\LogoSetting;
use App\Traits\Image\ImageDelete;
use App\Traits\Image\ImageUpload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LogoService implements LogoInterface
{
    use ImageUpload, ImageDelete;


    /**
     * @return LogoSetting|null
     */
    public function getLogo(): ?LogoSetting
    {
        return LogoSetting::query()->first();
    }

    /**
     * @param int $id
     *
     * @return LogoSetting|null
     */
    public function getLogoById(int $id): ?LogoSetting
    {
        return LogoSetting::query()->where('id', $id)->first();
    }

    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    public function createOrUpdateFavicon(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting
    {
        try {
            $logoSetting = $this->getLogo();

            if (!empty($logoSetting)) {
                return $this->faviconUpdate(logoUpdateDTO: $logoUpdateDTO, logoSetting: $logoSetting);
            }

            return $this->faviconCreate(logoUpdateDTO: $logoUpdateDTO);
        } catch (\Exception $exception) {
            Log::error("LogoService (createOrUpdateFavicon) : ", context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    private function faviconCreate(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting
    {
        try {
            $image = $this->imageUpload(file: $logoUpdateDTO->logo, path: 'logos', width: 100, height: 100);

            return LogoSetting::query()->create([
                'setting_id'   => $logoUpdateDTO->settingId,
                'favicon'      => $image['image'],
                'favicon_type' => $image['type'],
                'updated_by'   => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("LogoService (faviconCreate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     * @param LogoSetting $logoSetting
     *
     * @return LogoSetting|null
     */
    private function faviconUpdate(LogoUpdateDTO $logoUpdateDTO, LogoSetting $logoSetting): ?LogoSetting
    {
        try {

            if (!empty($logoSetting?->favicon)) {
                $this->imageDelete(image: $logoSetting->favicon);
            }

            $image = $this->imageUpload(file: $logoUpdateDTO->logo, path: 'logos', width: 100, height: 100);

            $result = $logoSetting->query()->update([
                'favicon'      => $image['image'],
                'favicon_type' => $image['type'],
                'updated_by'   => request()->user()->id,
                'updated_at'   => Carbon::now(),
            ]);

            if ($result) {
                // Mevcut model üzerinde güncellemeleri elle yapıyoruz
                $logoSetting->favicon = $image['image'];
                $logoSetting->favicon_type = $image['type'];

                return $logoSetting; // Güncellenmiş modeli döndürüyoruz
            }

            return null;
        } catch (\Exception $exception) {
            Log::error("LogoService (faviconUpdate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }



    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    public function createOrUpdateHeaderWhite(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting
    {
        try {
            $logoSetting = $this->getLogo();

            if (!empty($logoSetting)) {
                return $this->headerWhiteUpdate(logoUpdateDTO: $logoUpdateDTO, logoSetting: $logoSetting);
            }

            return $this->headerWhiteCreate(logoUpdateDTO: $logoUpdateDTO);
        } catch (\Exception $exception) {
            Log::error("LogoService (createOrUpdateHeaderWhite) : ", context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    private function headerWhiteCreate(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting
    {
        try {
            $image = $this->imageUpload(file: $logoUpdateDTO->logo, path: 'logos', width: 250, height: 100);

            return LogoSetting::query()->create([
                'setting_id'        => $logoUpdateDTO->settingId,
                'header_white'      => $image['image'],
                'header_white_type' => $image['type'],
                'updated_by'        => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("LogoService (headerWhiteCreate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     * @param LogoSetting $logoSetting
     *
     * @return LogoSetting|null
     */
    private function headerWhiteUpdate(LogoUpdateDTO $logoUpdateDTO, LogoSetting $logoSetting): ?LogoSetting
    {
        try {

            if (!empty($logoSetting?->header_white)) {
                $this->imageDelete(image: $logoSetting->header_white);
            }

            $image = $this->imageUpload(file: $logoUpdateDTO->logo, path: 'logos', width: 250, height: 100);

            $result = $logoSetting->query()->update([
                'header_white'      => $image['image'],
                'header_white_type' => $image['type'],
                'updated_by'        => request()->user()->id,
                'updated_at'        => Carbon::now(),
            ]);

            if ($result) {
                // Mevcut model üzerinde güncellemeleri elle yapıyoruz
                $logoSetting->header_white      = $image['image'];
                $logoSetting->header_white_type = $image['type'];

                return $logoSetting; // Güncellenmiş modeli döndürüyoruz
            }

            return null;
        } catch (\Exception $exception) {
            Log::error("LogoService (headerWhiteUpdate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }



    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    public function createOrUpdateHeaderDark(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting
    {
        try {
            $logoSetting = $this->getLogo();

            if (!empty($logoSetting)) {
                return $this->headerDarkUpdate(logoUpdateDTO: $logoUpdateDTO, logoSetting: $logoSetting);
            }

            return $this->headerDarkCreate(logoUpdateDTO: $logoUpdateDTO);
        } catch (\Exception $exception) {
            Log::error("LogoService (createOrUpdateHeaderDark) : ", context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    private function headerDarkCreate(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting
    {
        try {
            $image = $this->imageUpload(file: $logoUpdateDTO->logo, path: 'logos', width: 250, height: 100);

            return LogoSetting::query()->create([
                'setting_id'       => $logoUpdateDTO->settingId,
                'header_dark'      => $image['image'],
                'header_dark_type' => $image['type'],
                'updated_by'       => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("LogoService (headerDarkCreate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     * @param LogoSetting $logoSetting
     *
     * @return LogoSetting|null
     */
    private function headerDarkUpdate(LogoUpdateDTO $logoUpdateDTO, LogoSetting $logoSetting): ?LogoSetting
    {
        try {

            if (!empty($logoSetting?->header_dark)) {
                $this->imageDelete(image: $logoSetting->header_dark);
            }

            $image = $this->imageUpload(file: $logoUpdateDTO->logo, path: 'logos', width: 250, height: 100);

            $result = $logoSetting->query()->update([
                'header_dark'      => $image['image'],
                'header_dark_type' => $image['type'],
                'updated_by'       => request()->user()->id,
                'updated_at'       => Carbon::now(),
            ]);

            if ($result) {
                // Mevcut model üzerinde güncellemeleri elle yapıyoruz
                $logoSetting->header_dark      = $image['image'];
                $logoSetting->header_dark_type = $image['type'];

                return $logoSetting; // Güncellenmiş modeli döndürüyoruz
            }

            return null;
        } catch (\Exception $exception) {
            Log::error("LogoService (headerDarkUpdate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }



    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    public function createOrUpdateFooterWhite(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting
    {
        try {
            $logoSetting = $this->getLogo();

            if (!empty($logoSetting)) {
                return $this->footerWhiteUpdate(logoUpdateDTO: $logoUpdateDTO, logoSetting: $logoSetting);
            }

            return $this->footerWhiteCreate(logoUpdateDTO: $logoUpdateDTO);
        } catch (\Exception $exception) {
            Log::error("LogoService (createOrUpdateFooterWhite) : ", context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    private function footerWhiteCreate(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting
    {
        try {
            $image = $this->imageUpload(file: $logoUpdateDTO->logo, path: 'logos', width: 250, height: 100);

            return LogoSetting::query()->create([
                'setting_id'        => $logoUpdateDTO->settingId,
                'footer_white'      => $image['image'],
                'footer_white_type' => $image['type'],
                'updated_by'        => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("LogoService (footerWhiteCreate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     * @param LogoSetting $logoSetting
     *
     * @return LogoSetting|null
     */
    private function footerWhiteUpdate(LogoUpdateDTO $logoUpdateDTO, LogoSetting $logoSetting): ?LogoSetting
    {
        try {

            if (!empty($logoSetting?->footer_white)) {
                $this->imageDelete(image: $logoSetting->footer_white);
            }

            $image = $this->imageUpload(file: $logoUpdateDTO->logo, path: 'logos', width: 250, height: 100);

            $result = $logoSetting->query()->update([
                'footer_white'      => $image['image'],
                'footer_white_type' => $image['type'],
                'updated_by'        => request()->user()->id,
                'updated_at'        => Carbon::now(),
            ]);

            if ($result) {
                // Mevcut model üzerinde güncellemeleri elle yapıyoruz
                $logoSetting->footer_white      = $image['image'];
                $logoSetting->footer_white_type = $image['type'];

                return $logoSetting; // Güncellenmiş modeli döndürüyoruz
            }

            return null;
        } catch (\Exception $exception) {
            Log::error("LogoService (footerWhiteUpdate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    public function createOrUpdateFooterDark(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting
    {
        try {
            $logoSetting = $this->getLogo();

            if (!empty($logoSetting)) {
                return $this->footerDarkUpdate(logoUpdateDTO: $logoUpdateDTO, logoSetting: $logoSetting);
            }

            return $this->footerDarkCreate(logoUpdateDTO: $logoUpdateDTO);
        } catch (\Exception $exception) {
            Log::error("LogoService (createOrUpdateFooterDark) : ", context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    private function footerDarkCreate(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting
    {
        try {
            $image = $this->imageUpload(file: $logoUpdateDTO->logo, path: 'logos', width: 250, height: 100);

            return LogoSetting::query()->create([
                'setting_id'       => $logoUpdateDTO->settingId,
                'footer_dark'      => $image['image'],
                'footer_dark_type' => $image['type'],
                'updated_by'       => request()->user()->id,
            ]);
        } catch (\Exception $exception) {
            Log::error("LogoService (footerDarkCreate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     * @param LogoSetting $logoSetting
     *
     * @return LogoSetting|null
     */
    private function footerDarkUpdate(LogoUpdateDTO $logoUpdateDTO, LogoSetting $logoSetting): ?LogoSetting
    {
        try {

            if (!empty($logoSetting?->footer_dark)) {
                $this->imageDelete(image: $logoSetting->footer_dark);
            }

            $image = $this->imageUpload(file: $logoUpdateDTO->logo, path: 'logos', width: 250, height: 100);

            $result = $logoSetting->query()->update([
                'footer_dark'      => $image['image'],
                'footer_dark_type' => $image['type'],
                'updated_by'       => request()->user()->id,
                'updated_at'       => Carbon::now(),
            ]);

            if ($result) {
                // Mevcut model üzerinde güncellemeleri elle yapıyoruz
                $logoSetting->footer_dark      = $image['image'];
                $logoSetting->footer_dark_type = $image['type'];

                return $logoSetting; // Güncellenmiş modeli döndürüyoruz
            }

            return null;
        } catch (\Exception $exception) {
            Log::error("LogoService (footerDarkUpdate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}
