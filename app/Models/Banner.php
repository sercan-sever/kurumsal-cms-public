<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Defaults\StatusEnum;
use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use App\Traits\Model\HasImage;
use App\Traits\Model\HasStatusInput;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string|null $image
 * @property string|null $type
 * @property int|null $sorting
 * @property StatusEnum $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property string|null $deleted_description
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class Banner extends Model
{
    use HasFactory, HasStatusInput, HasCrudUser, HasCrudUserAt, HasImage, SoftDeletes;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'type',
        'status',
        'sorting',
        'deleted_description',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
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

        return $this->belongsTo(BannerContent::class, 'id', 'banner_id')->where('language_id', $siteLangID)->withDefault();
    }


    /**
     * @return HasMany
     */
    public function allContent(): HasMany
    {
        return $this->hasMany(BannerContent::class);
    }
}
