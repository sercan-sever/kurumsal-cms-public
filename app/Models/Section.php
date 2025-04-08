<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Pages\Section\PageSectionEnum;
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
 *
 * @property int $page_id
 *
 * @property string|null $title
 * @property string|null $slug
 *
 * @property string|null $image
 * @property string|null $type
 * @property string|null $other_image
 * @property string|null $other_type
 *
 * @property int|null $limit
 * @property int|null $sorting
 *
 * @property PageSectionEnum $section_type
 * @property StatusEnum $status
 * @property StatusEnum $default
 *
 * @property string|null $deleted_description
 *
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 *
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class Section extends Model
{
    use HasFactory, HasStatusInput, HasCrudUser, HasCrudUserAt, HasImage, SoftDeletes;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'page_id',

        'title',
        'slug',

        'image',
        'type',
        'other_image',
        'other_type',

        'limit',
        'sorting',

        'section_type',
        'status',
        'default',

        'deleted_description',

        'created_by',
        'updated_by',
        'deleted_by',

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
            'section_type' => PageSectionEnum::class,
            'status'       => StatusEnum::class,
            'default'      => StatusEnum::class,
            'created_at'   => 'datetime',
            'updated_at'   => 'datetime',
            'deleted_at'   => 'datetime',
        ];
    }


    /**
     * @return BelongsTo
     */
    public function content(): BelongsTo
    {
        $siteLangID = request(key: 'siteLangID', default: '');

        return $this->belongsTo(SectionContent::class, 'id', 'section_id')->where('language_id', $siteLangID)->withDefault();
    }


    /**
     * @return BelongsTo
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id')->withDefault();
    }


    /**
     * @return HasMany
     */
    public function allContent(): HasMany
    {
        return $this->hasMany(SectionContent::class);
    }


    /**
     * @param string
     */
    public function getSectionCategoryName(): string
    {
        return PageSectionEnum::getSectionCategoryName(section: $this->section_type?->value);
    }


    /**
     * @return bool
     */
    public function isDefaultStatus(): bool
    {
        return $this->default->value == StatusEnum::ACTIVE->value;
    }
}
