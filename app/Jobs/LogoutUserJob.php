<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\SendLogoutUserNotification;
use App\Models\EmailSetting;
use App\Models\User;
use App\Services\Backend\Settings\Email\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class LogoutUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param User $user
     * @param string $ip
     * @param string $date
     *
     * @return void
     */
    public function __construct(public User $user, public string $ip, public string $date)
    {
        //
    }

    /**
     * @param EmailSetting $emailSetting
     * @param EmailService $emailService
     *
     * @return void
     */
    public function handle(EmailSetting $emailSetting, EmailService $emailService): void
    {
        $emailSetting = $emailService->getModel();

        if ($emailService->syncMailConfigWithDatabaseCheck(emailSetting: $emailSetting)) {

            $emailService->syncMailConfigWithDatabase(emailSetting: $emailSetting);

            Mail::to(users: $emailSetting?->notification_email, name: 'Localkod - Çıkış Yapan Kullanıcı')->send(new SendLogoutUserNotification(
                user: $this->user,
                ip: $this->ip,
                date: $this->date,
                emailSetting: $emailSetting,
            ));
        }
    }
}
