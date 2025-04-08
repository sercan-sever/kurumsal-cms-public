<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 */
class Setting extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
    ];


    /**
     * @return BelongsTo
     */
    public function general(): BelongsTo
    {
        return $this->belongsTo(GeneralSetting::class, 'id', 'setting_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(AddressSetting::class, 'id', 'setting_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function social(): BelongsTo
    {
        return $this->belongsTo(SocialSetting::class, 'id', 'setting_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function logo(): BelongsTo
    {
        return $this->belongsTo(LogoSetting::class, 'id', 'setting_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function plugin(): BelongsTo
    {
        return $this->belongsTo(PluginSetting::class, 'id', 'setting_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function email(): BelongsTo
    {
        return $this->belongsTo(EmailSetting::class, 'id', 'setting_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function subscribe(): BelongsTo
    {
        return $this->belongsTo(SubscribeSetting::class, 'id', 'setting_id')->withDefault();
    }
}
