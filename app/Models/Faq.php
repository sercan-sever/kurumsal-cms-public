<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Defaults\StatusEnum;
use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use App\Traits\Model\HasStatusInput;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $sorting
 * @property StatusEnum $status
 * @property string|null $deleted_description
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class Faq extends Model
{
    use HasFactory, HasStatusInput, HasCrudUser, HasCrudUserAt, SoftDeletes;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'sorting',
        'deleted_description',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @return array<string, string|StatusEnum>
     */
    protected function casts(): array
    {
        return [
            'status'     => StatusEnum::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }


    /**
     * @return BelongsTo
     */
    public function content(): BelongsTo
    {
        $siteLangID = request(key: 'siteLangID', default: '');

        return $this->belongsTo(FaqContent::class, 'id', 'faq_id')->where('language_id', $siteLangID)->withDefault();
    }


    /**
     * @return HasMany
     */
    public function allContent(): HasMany
    {
        return $this->hasMany(FaqContent::class);
    }
}
