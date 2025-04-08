<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property int $brand_id
 * @property int $language_id
 * @property string|null $title
 * @property string|null $description
 */
class BrandContent extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'brand_id',
        'language_id',
        'title',
        'slug',
        'description',
    ];
}
