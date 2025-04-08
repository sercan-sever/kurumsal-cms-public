<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $address_setting_id
 * @property int $language_id
 *
 * @property string|null $email_title_one
 * @property string|null $email_address_one
 * @property string|null $email_title_two
 * @property string|null $email_address_two
 *
 * @property string|null $phone_title_one
 * @property string|null $phone_number_one
 * @property string|null $phone_title_two
 * @property string|null $phone_number_two
 *
 * @property string|null $address_title_one
 * @property string|null $address_content_one
 * @property string|null $address_iframe_one
 *
 * @property string|null $address_title_two
 * @property string|null $address_content_two
 * @property string|null $address_iframe_two
 */
class AddressSettingContent extends Model
{
    use HasFactory;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'address_setting_id',
        'language_id',

        'email_title_one',
        'email_address_one',
        'email_title_two',
        'email_address_two',

        'phone_title_one',
        'phone_number_one',
        'phone_title_two',
        'phone_number_two',

        'address_title_one',
        'address_content_one',
        'address_iframe_one',

        'address_title_two',
        'address_content_two',
        'address_iframe_two',
    ];
}
