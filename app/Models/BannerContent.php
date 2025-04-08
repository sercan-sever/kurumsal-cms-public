<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $banner_id
 * @property int $language_id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $button_title
 * @property string|null $url
 */
class BannerContent extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'banner_id',
        'language_id',
        'title',
        'description',
        'button_title',
        'url',
    ];
}
