<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Jobs\JobStatusEnum;
use App\Observers\TranslationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $language_id
 * @property JobStatusEnum $status
 */
#[ObservedBy([TranslationObserver::class])]
class Translation extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'language_id',
        'status',
    ];


    /**
     * @return array<string, JobStatusEnum>
     */
    protected function casts(): array
    {
        return [
            'status' => JobStatusEnum::class,
        ];
    }


    /**
     * @return HasMany
     */
    public function contents(): HasMany
    {
        return $this->hasMany(TranslationContent::class);
    }

    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class)->withDefault();
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status == JobStatusEnum::COMPLETED;
    }
}
