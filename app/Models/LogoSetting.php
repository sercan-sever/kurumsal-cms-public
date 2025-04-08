<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

/**
 * @property int $id
 * @property int $setting_id
 *
 * @property string|null $favicon
 * @property string|null $favicon_type
 *
 * @property string|null $header_white
 * @property string|null $header_white_type
 *
 * @property string|null $header_dark
 * @property string|null $header_dark_type
 *
 * @property string|null $footer_white
 * @property string|null $footer_white_type
 *
 * @property string|null $footer_dark
 * @property string|null $footer_dark_type
 *
 * @property int|null $updated_by
 *
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class LogoSetting extends Model
{
    use HasFactory, HasCrudUser, HasCrudUserAt;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'setting_id',

        'favicon',
        'favicon_type',

        'header_white',
        'header_white_type',

        'header_dark',
        'header_dark_type',

        'footer_white',
        'footer_white_type',

        'footer_dark',
        'footer_dark_type',

        'updated_by',
        'created_at',
        'updated_at',
    ];


    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }


    /**
     * @return string
     */
    public function getBackendFavicon(): string
    {
        if (!empty($this->favicon) && File::exists(public_path($this->favicon))) {
            return asset($this->favicon);
        }

        return asset('backend/assets/media/svg/files/blank-image.svg');
    }

    /**
     * @return string
     */
    public function getBackendHeaderWhite(): string
    {
        if (!empty($this->header_white) && File::exists(public_path($this->header_white))) {
            return asset($this->header_white);
        }

        return asset('backend/assets/media/svg/files/blank-image.svg');
    }

    /**
     * @return string
     */
    public function getBackendHeaderDark(): string
    {
        if (!empty($this->header_dark) && File::exists(public_path($this->header_dark))) {
            return asset($this->header_dark);
        }

        return asset('backend/assets/media/svg/files/blank-image.svg');
    }

    /**
     * @return string
     */
    public function getBackendFooterWhite(): string
    {
        if (!empty($this->footer_white) && File::exists(public_path($this->footer_white))) {
            return asset($this->footer_white);
        }

        return asset('backend/assets/media/svg/files/blank-image.svg');
    }

    /**
     * @return string
     */
    public function getBackendFooterDark(): string
    {
        if (!empty($this->footer_dark) && File::exists(public_path($this->footer_dark))) {
            return asset($this->footer_dark);
        }

        return asset('backend/assets/media/svg/files/blank-image.svg');
    }
}
