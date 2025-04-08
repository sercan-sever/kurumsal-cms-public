<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Defaults\StatusEnum;
use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use App\Traits\Model\HasStatusInput;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $language_id
 * @property string $email
 * @property string|null $ip_address
 * @property string|null $deleted_description
 * @property StatusEnum $status
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class BlogSubscribe extends Model
{
    use HasFactory, HasStatusInput, HasCrudUser, HasCrudUserAt, SoftDeletes;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'language_id',
        'email',
        'ip_address',
        'deleted_description',
        'status',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    /**
     * @return array<string, string|StatusEnum>
     */
    protected function casts(): array
    {
        return [
            'status'     => StatusEnum::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }


    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id', 'id')->withDefault();
    }
}
