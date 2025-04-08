<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Defaults\StatusEnum;
use App\Observers\LanguageObserver;
use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use App\Traits\Model\HasImage;
use App\Traits\Model\HasStatusInput;
use DateTime;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property string|null $image
 * @property string|null $type
 * @property StatusEnum $status
 * @property StatusEnum $default
 * @property int|null $sorting
 * @property string|null $deleted_description
 *
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 *
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
#[ObservedBy([LanguageObserver::class])]
class Language extends Model
{
    use HasFactory, HasStatusInput, HasCrudUser, HasCrudUserAt, HasImage, SoftDeletes;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'image',
        'type',
        'status',
        'default',
        'sorting',
        'deleted_description',

        'created_by',
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
            'default'    => StatusEnum::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }


    /**
     * @return string
     */
    public function getCodeUppercase(): string
    {
        return str($this->code)->upper()->toString();
    }

    /**
     * @return bool
     */
    public function isActiveDefault(): bool
    {
        return $this->default->value == StatusEnum::ACTIVE->value;
    }


    /**
     * @return string
     */
    public function getDefaultIcon(): string
    {
        $checked = $this->default->value == StatusEnum::ACTIVE->value ? true : false;
        $color = $checked ? 'success' : 'danger';
        $icon = $checked ? 'check' : 'xmark';

        return '<span class="badge badge-circle badge-' . $color . '">
                    <i class="fa-solid text-white fa-' . $icon . '"></i>
                </span>';
    }
}
