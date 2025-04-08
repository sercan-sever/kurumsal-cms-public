<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $faq_id
 * @property int $language_id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $description
 */
class FaqContent extends Model
{
    use HasFactory;


        /**
     * @var array<int, string>
     */
    protected $fillable = [
        'faq_id',
        'language_id',
        'title',
        'slug',
        'description',
    ];
}
