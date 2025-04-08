<?php

declare(strict_types=1);

namespace App\Services\Backend\Settings\Email;

use App\Interfaces\DTO\BaseDTOInterface;
use App\Interfaces\Settings\Email\EmailInterface;
use App\Models\EmailSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService implements EmailInterface
{
    /**
     * @return EmailSetting|null
     */
    public function getModel(): ?EmailSetting
    {
        return Cache::remember('email_setting', 3600, function () { // 1 saat boyunca cacheleme yapıyor.
            return EmailSetting::query()->first();
        });
    }


    /**
     * @param int $id
     *
     * @return EmailSetting|null
     */
    public function getEmailById(int $id): ?EmailSetting
    {
        return EmailSetting::query()->where('id', $id)->first();
    }


    /**
     * @param BaseDTOInterface $emailUpdateDTO
     *
     * @return EmailSetting|null
     */
    public function createOrUpdate(BaseDTOInterface $emailUpdateDTO): ?EmailSetting
    {
        try {
            return EmailSetting::query()->updateOrCreate(
                ['setting_id' => $emailUpdateDTO->settingId],
                [
                    'notification_email' => $emailUpdateDTO->notificatioEmail,
                    'sender_email'       => $emailUpdateDTO->senderEmail,
                    'subject'            => $emailUpdateDTO->subject,
                    'host'               => $emailUpdateDTO->host,
                    'port'               => $emailUpdateDTO->port,
                    'encryption'         => $emailUpdateDTO->encryption,
                    'username'           => $emailUpdateDTO->username,
                    'password'           => Crypt::encrypt($emailUpdateDTO->password),
                    'updated_by'         => request()->user()->id,
                    'updated_at'         => Carbon::now(),
                ]
            );
        } catch (\Exception $exception) {
            Log::error("EmailService (createOrUpdate) : ", context: [$exception->getMessage()]);
            return null;
        }
    }


    /**
     * @param EmailSetting|null $emailSetting
     *
     * @return void
     */
    public function syncMailConfigWithDatabase(?EmailSetting $emailSetting): void
    {
        if (!empty($emailSetting)) {
            // Ayarları güncelle
            config([
                'mail.default' => $emailSetting?->getEngineValue(),
                'mail.from.address' => $emailSetting?->sender_email,
                'mail.mailers.smtp.transport' => $emailSetting?->getEngineValue(),
                'mail.mailers.smtp.host' => $emailSetting?->host,
                'mail.mailers.smtp.port' => $emailSetting?->port,
                'mail.mailers.smtp.encryption' => $emailSetting?->encryption?->value,
                'mail.mailers.smtp.username' => $emailSetting?->username,
                'mail.mailers.smtp.password' => $emailSetting?->getPassword(),
                'mail.mailers.smtp.timeout' => $emailSetting?->timeout,
            ]);

            Mail::purge(); // Mail cache'ini temizle
        }
    }


    /**
     * @param EmailSetting|null $emailSetting
     *
     * @return bool
     */
    public function syncMailConfigWithDatabaseCheck(?EmailSetting $emailSetting): bool
    {
        if (
            empty($emailSetting?->sender_email)
            ||
            empty($emailSetting?->host)
            ||
            empty($emailSetting?->port)
            ||
            empty($emailSetting?->encryption?->value)
            ||
            empty($emailSetting?->username)
            ||
            empty($emailSetting?->getPassword())
            ||
            empty($emailSetting?->timeout)
        ) {
            return false;
        }


        return true;
    }
}
