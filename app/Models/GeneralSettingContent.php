<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $general_setting_id
 * @property int $language_id
 * @property string|null $title
 * @property string|null $meta_keywords
 * @property string|null $meta_descriptions
 */
class GeneralSettingContent extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'general_setting_id',
        'language_id',
        'title',
        'meta_keywords',
        'meta_descriptions',
    ];
}
