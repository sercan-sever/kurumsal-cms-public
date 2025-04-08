<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Emails\EmailEncryptionEnum;
use App\Enums\Emails\EmailEngineEnum;
use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * @property int $id
 * @property int $setting_id
 *
 * @property string|null $notification_email
 * @property string|null $sender_email
 * @property string|null $subject
 *
 * @property EmailEngineEnum $engine
 * @property string|null $host
 * @property int $port
 * @property EmailEncryptionEnum $encryption
 *
 * @property string|null $username
 * @property string|null $password
 *
 * @property int $timeout
 * @property int|null $updated_by
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class EmailSetting extends Model
{
    use HasFactory, HasCrudUser, HasCrudUserAt;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'setting_id',

        'notification_email',
        'sender_email',
        'subject',

        'engine',
        'host',
        'port',
        'encryption',

        'username',
        'password',

        'timeout',

        'updated_by',
        'created_at',
        'updated_at',
    ];


    /**
     * @return array<string, EmailEngineEnum|EmailEncryptionEnum>
     */
    protected function casts(): array
    {
        return [
            'engine'       => EmailEngineEnum::class,
            'encryption'   => EmailEncryptionEnum::class,
            'created_at'   => 'datetime',
            'updated_at'   => 'datetime',
        ];
    }


    /**
     * @return string
     */
    public function getEngineValue(): string
    {
        return EmailEngineEnum::getValue(engine: $this->engine?->value);
    }


    /**
     * @return string
     */
    public function getPassword(): string
    {
        return (string)Crypt::decrypt($this->password);
    }
}
