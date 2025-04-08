<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property int $about_id
 * @property int $language_id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $short_description
 * @property string|null $mission
 * @property string|null $vision
 * @property string|null $meta_keywords
 * @property string|null $meta_descriptions
 */
class AboutContent extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'about_id',
        'language_id',
        'title',
        'slug',
        'description',
        'short_description',
        'mission',
        'vision',
        'meta_keywords',
        'meta_descriptions',
    ];
}
