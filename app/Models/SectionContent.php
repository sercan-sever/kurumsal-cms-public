<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $section_id
 * @property int $language_id
 * @property string|null $sub_heading
 * @property string|null $heading
 * @property string|null $button_title
 * @property string|null $description
 * @property string|null $short_description
 */
class SectionContent extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'section_id',
        'language_id',
        'sub_heading',
        'heading',
        'button_title',
        'description',
        'short_description',
    ];
}
