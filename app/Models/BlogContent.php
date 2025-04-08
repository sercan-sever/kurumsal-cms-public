<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $blog_id
 * @property int $language_id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $meta_keywords
 * @property string|null $meta_descriptions
 */
class BlogContent extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'blog_id',
        'language_id',
        'title',
        'slug',
        'description',
        'meta_keywords',
        'meta_descriptions',
    ];


    /**
     * @return BelongsTo
     */
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'id')->withDefault();
    }


    /**
     * @return BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id')->withDefault();
    }
}
