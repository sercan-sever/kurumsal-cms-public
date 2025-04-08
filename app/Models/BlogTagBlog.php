<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $blog_id
 * @property int $blog_tag_id
 * @property int $language_id
 */
class BlogTagBlog extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'blog_id',
        'blog_tag_id',
        'language_id',
    ];
}
