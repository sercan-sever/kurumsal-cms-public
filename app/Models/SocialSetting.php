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
 * @property string|null $facebook
 * @property string|null $twitter
 * @property string|null $instagram
 * @property string|null $linkedin
 * @property string|null $youtube
 * @property string|null $pinterest
 * @property string|null $whatsapp
 * @property int|null $updated_by
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class SocialSetting extends Model
{
    use HasFactory, HasCrudUser, HasCrudUserAt;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'setting_id',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'pinterest',
        'whatsapp',
        'updated_by',
        'created_at',
        'updated_at',
    ];


    /**
     * @return array<string, EmailEngineEnum|EmailEncryptionEnum>
     */
    protected function casts(): array
    {
        return [
            'created_at'   => 'datetime',
            'updated_at'   => 'datetime',
        ];
    }
}
