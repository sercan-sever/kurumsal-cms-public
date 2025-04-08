<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use App\Traits\Model\HasImage;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string|null $image
 * @property string|null $type
 * @property string|null $other_image
 * @property string|null $other_type
 * @property int|null $updated_by
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class About extends Model
{
    use HasFactory, HasCrudUser, HasCrudUserAt, HasImage;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'type',
        'other_image',
        'other_type',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];


    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }


    /**
     * @return BelongsTo
     */
    public function content(): BelongsTo
    {
        $siteLangID = request(key: 'siteLangID', default: '');

        return $this->belongsTo(AboutContent::class, 'id', 'about_id')->where('language_id', $siteLangID)->withDefault();
    }


    /**
     * @return HasMany
     */
    public function allContent(): HasMany
    {
        return $this->hasMany(AboutContent::class);
    }
}
