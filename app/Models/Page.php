<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Pages\Menu\PageMenuEnum;
use App\Enums\Pages\Page\SubPageDesignEnum;
use App\Traits\Model\HasBreadcrumbInput;
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
 *
 * @property int|null $top_page
 *
 * @property string|null $image
 * @property string|null $type
 *
 * @property int|null $sorting
 *
 * @property PageMenuEnum $menu
 * @property SubPageDesignEnum $design
 * @property StatusEnum $status
 * @property StatusEnum $breadcrumb
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
class Page extends Model
{
    use HasFactory, HasStatusInput, HasBreadcrumbInput, HasCrudUser, HasCrudUserAt, HasImage, SoftDeletes;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'top_page',

        'image',
        'type',

        'sorting',

        'menu',
        'design',
        'status',
        'breadcrumb',

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
            'menu'       => PageMenuEnum::class,
            'design'     => SubPageDesignEnum::class,
            'status'     => StatusEnum::class,
            'breadcrumb' => StatusEnum::class,
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

        return $this->belongsTo(PageContent::class, 'id', 'page_id')->where('language_id', $siteLangID)->withDefault();
    }


    /**
     * @return HasMany
     */
    public function allContent(): HasMany
    {
        return $this->hasMany(PageContent::class);
    }


    /**
     * @return BelongsTo
     */
    public function topPage(): BelongsTo
    {
        return $this->belongsTo(self::class, 'top_page')->withDefault();
    }

    /**
     * @return HasMany
     */
    public function subPages(): HasMany
    {
        return $this->hasMany(self::class, 'top_page');
    }

    /**
     * @return HasMany
     */
    public function subPageMenus(): HasMany
    {
        return $this->hasMany(self::class, 'top_page')->where('status', StatusEnum::ACTIVE);
    }


    /**
     * @return BelongsToMany
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'page_section_pages', 'page_id', 'section_id')->withPivot('sorting')->orderBy('page_section_pages.sorting', 'asc');
    }


    /**
     * @return BelongsToMany
     */
    public function sectionActives(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'page_section_pages', 'page_id', 'section_id')
        ->withPivot('sorting')
        ->where('status', StatusEnum::ACTIVE)
        ->orderBy('page_section_pages.sorting', 'asc');
    }


    /**
     * @return bool
     */
    public function hasSubPages(): bool
    {
        return $this->subPages()->exists(); // Alt sayfaları olup olmadığını kontrol et
    }


    /**
     * @return string
     */
    public function getTopPageNameAttribute(): string
    {
        if (!empty($this->topPage?->id)) {
            return '<span class="badge badge-info p-3">' . $this->topPage?->content?->title . '</span>';
        }

        return '<span class="badge badge-success p-3">Ana Menü</span>';
    }
}
