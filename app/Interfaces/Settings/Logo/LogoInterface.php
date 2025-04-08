<?php

declare(strict_types=1);

namespace App\Interfaces\Settings\Logo;

use App\DTO\Backend\Settings\Logo\LogoUpdateDTO;
use App\Models\LogoSetting;

interface LogoInterface
{
    /**
     * @return LogoSetting|null
     */
    public function getLogo(): ?LogoSetting;


    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    public function createOrUpdateFavicon(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting;


    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    public function createOrUpdateHeaderWhite(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting;


    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    public function createOrUpdateHeaderDark(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting;


    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    public function createOrUpdateFooterWhite(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting;


    /**
     * @param LogoUpdateDTO $logoUpdateDTO
     *
     * @return LogoSetting|null
     */
    public function createOrUpdateFooterDark(LogoUpdateDTO $logoUpdateDTO): ?LogoSetting;
}
