<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $blog_tag_id
 * @property int $language_id
 * @property string|null $title
 * @property string|null $slug
 */
class BlogTagContent extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'blog_tag_id',
        'language_id',
        'title',
        'slug',
    ];
}
