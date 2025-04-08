<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $setting_id
 *
 * @property string|null $recaptcha_site_key
 * @property string|null $recaptcha_secret_key
 * @property string|null $analytics_four
 *
 * @property int|null $updated_by
 *
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class PluginSetting extends Model
{
    use HasFactory, HasCrudUser, HasCrudUserAt;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'setting_id',
        'recaptcha_site_key',
        'recaptcha_secret_key',
        'analytics_four',
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
}
