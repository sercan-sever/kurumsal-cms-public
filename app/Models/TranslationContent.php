<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Defaults\StatusEnum;
use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $translation_id
 * @property string|null $group
 * @property string|null $key
 * @property string|null $value
 * @property StatusEnum $status
 *
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class TranslationContent extends Model
{
    use HasFactory, HasCrudUser, HasCrudUserAt;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'translation_id',
        'group',
        'key',
        'value',
        'default',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];


    /**
     * @return array<string, StatusEnum|string>
     */
    protected function casts(): array
    {
        return [
            'default'    => StatusEnum::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }


    /**
     * @return BelongsTo
     */
    public function translation(): BelongsTo
    {
        return $this->belongsTo(Translation::class)->withDefault();
    }

    /**
     * @return bool
     */
    public function isDeletable(): bool
    {
        return $this->default == StatusEnum::PASSIVE;
    }
}
