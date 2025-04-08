<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $page_id
 * @property int $language_id
 *
 * @property string|null $title
 * @property string|null $slug
 *
 * @property string|null $meta_keywords
 * @property string|null $meta_descriptions
 */
class PageContent extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'page_id',
        'language_id',
        'title',
        'slug',
        'meta_keywords',
        'meta_descriptions',
    ];


    /**
     * @return BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id', 'id')->withDefault();
    }


    /**
     * @return BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id')->withDefault();
    }
}
