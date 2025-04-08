<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings\Email;

use App\Enums\Emails\EmailEncryptionEnum;
use App\Rules\DisposableEmailCheckRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmailRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'setting_id' => [
                'required',
                'integer',
                'min:1',
                'exists:settings,id'
            ],

            'notification_email' => [
                'required',
                'string',
                'min:6',
                'max:200',
                'different:sender_email',
                'email:dns',
                new DisposableEmailCheckRule(),
            ],

            'sender_email' => [
                'required',
                'string',
                'min:6',
                'max:200',
                'different:notification_email',
                'email:dns',
                new DisposableEmailCheckRule(),
            ],

            'subject' => [
                'required',
                'string',
                'min:1',
                'max:200',
            ],

            'host' => [
                'required',
                'string',
                'min:1',
                'max:200'
            ],

            'port' => [
                'required',
                'integer',
                'min:1',
                'max:65535'
            ],

            'encryption' => [
                'required',
                Rule::in(EmailEncryptionEnum::cases())
            ],

            'username' => [
                'required',
                'string',
                'min:1',
                'max:200'
            ],

            'password' => [
                'required',
                'string',
                'min:1',
                'max:200'
            ],
        ];
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function attributes(): array
    {
        return [
            'setting_id'         => 'Ayar ID',
            'notification_email' => 'Bildirimleri Alıcak E-Mail Adresi',
            'sender_email'       => 'Gönderici E-Mail Adresi',
            'subject'            => 'E-Mail Başlık',
            'host'               => 'Host Adı',
            'port'               => 'Port',
            'encryption'         => 'Şifreleme',
            'username'           => 'Kullanıcı Adı',
            'password'           => 'Parola',
        ];
    }
}
