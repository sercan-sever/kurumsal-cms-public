<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Roles\RoleEnum;
use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use App\Traits\Model\HasImage;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $image
 * @property string|null $type
 * @property string|null $phone
 * @property string $password
 * @property string|null $deleted_description
 * @property string|null $banned_description
 *
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $banned_by
 * @property int|null $deleted_by
 *
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $banned_at
 * @property DateTime|null $deleted_at
 */
class User extends Authenticatable
{
    use HasFactory, HasRoles, HasCrudUser, HasImage, HasCrudUserAt, Notifiable, SoftDeletes;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'image',
        'type',
        'phone',
        'password',
        'deleted_description',
        'banned_description',

        'created_by',
        'updated_by',
        'banned_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'banned_at',
        'deleted_at',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',

            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
            'banned_at'         => 'datetime',
            'deleted_at'        => 'datetime',

            'password'          => 'hashed',
        ];
    }


    /**
     * @return BelongsTo
     */
    public function bannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by', 'id')->withDefault();
    }


    /**
     * @param string
     */
    public function getLimitName(): string
    {
        return getStringLimit(field: str($this->name)->title()->toString(), limit: 30);
    }

    /**
     * @param string
     */
    public function getLimitEmail(): string
    {
        return getStringLowerLimit(field: $this->email);
    }

    /**
     * @param bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(RoleEnum::SUPER_ADMIN);
    }

    /**
     * @param bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(RoleEnum::ADMIN);
    }

    /**
     * @param bool
     */
    public function isGuest(): bool
    {
        return $this->hasRole(RoleEnum::GUEST);
    }

    /**
     * @param bool
     */
    public function isBanned(): bool
    {
        return $this->hasRole(RoleEnum::BANNED);
    }

    /**
     * @param string
     */
    public function getRoleHtml(): string
    {
        foreach (RoleEnum::values() as $role) {
            if ($this->hasRole($role)) {
                return RoleEnum::getHtml($role);
            }
        }

        return RoleEnum::getHtml('');
    }

    /**
     * @param string
     */
    public function getRoleHeaderHtml(): string
    {
        foreach (RoleEnum::values() as $role) {
            if ($this->hasRole($role)) {
                return RoleEnum::getHeaderHtml($role);
            }
        }

        return RoleEnum::getHeaderHtml('');
    }
}
