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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property int $brand_id
 * @property string|null $image
 * @property string|null $type
 * @property string|null $other_image
 * @property string|null $other_type
 * @property int|null $sorting
 * @property StatusEnum $status
 * @property string|null $deleted_description
 *
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 *
 * @property DateTime|null $completion_date
 *
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class Reference extends Model
{
    use HasFactory, HasStatusInput, HasCrudUser, HasCrudUserAt, HasImage, SoftDeletes;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'brand_id',

        'image',
        'type',
        'other_image',
        'other_type',

        'sorting',
        'status',

        'deleted_description',

        'created_by',
        'updated_by',
        'deleted_by',

        'completion_date',

        'created_at',
        'updated_at',
        'deleted_at',
    ];


    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status'          => StatusEnum::class,
            'completion_date' => 'datetime',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
            'deleted_at'      => 'datetime',
        ];
    }


    /**
     * @return BelongsTo
     */
    public function content(): BelongsTo
    {
        $siteLangID = request(key: 'siteLangID', default: '');

        return $this->belongsTo(ReferenceContent::class, 'id', 'reference_id')->where('language_id', $siteLangID)->withDefault();
    }


    /**
     * @return HasMany
     */
    public function allContent(): HasMany
    {
        return $this->hasMany(ReferenceContent::class);
    }


    /**
     * @return HasMany
     */
    public function allImage(): HasMany
    {
        return $this->hasMany(ReferenceImage::class);
    }


    /**
     * @return BelongsToMany
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'reference_service_references', 'reference_id', 'service_id');
    }


    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id')->withDefault();
    }


    /**
     * @return string
     */
    public function getCompletionDate(): string
    {
        return !empty($this->completion_date) ? $this->completion_date->format('Y-m-d') : now()->format('Y-m-d');
    }


    /**
     * @return string
     */
    public function getCompletionDateFrontend(): string
    {
        return !empty($this->completion_date) ? $this->completion_date->format('d / m / Y') : now()->format('d / m / Y');
    }
}
