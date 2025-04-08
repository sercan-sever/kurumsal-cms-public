<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Defaults\StatusEnum;
use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use App\Traits\Model\HasStatusInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $setting_id
 * @property StatusEnum $status
 * @property int|null $updated_by
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class SubscribeSetting extends Model
{
    use HasFactory, HasStatusInput, HasCrudUser, HasCrudUserAt;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'setting_id',
        'status',
        'updated_by',
        'created_at',
        'updated_at',
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
        ];
    }
}
