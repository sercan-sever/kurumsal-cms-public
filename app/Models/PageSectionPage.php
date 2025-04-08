<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $page_id
 * @property int $section_id
 * @property int|null $sorting
 */
class PageSectionPage extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'page_id',
        'section_id',
        'sorting',
    ];


    /**
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id', 'id')->withDefault();
    }
}
