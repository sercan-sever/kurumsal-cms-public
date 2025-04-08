<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $setting_id
 * @property int $updated_by
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class GeneralSetting extends Model
{
    use HasFactory, HasCrudUser, HasCrudUserAt;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'setting_id',
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
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }


    /**
     * @return BelongsTo
     */
    public function content(): BelongsTo
    {
        $siteLangID = request(key: 'siteLangID', default: '');

        return $this->belongsTo(GeneralSettingContent::class, 'id', 'general_setting_id')->where('language_id', $siteLangID)->withDefault();
    }

    /**
     * @return HasMany
     */
    public function allContent(): HasMany
    {
        return $this->hasMany(GeneralSettingContent::class);
    }
}
