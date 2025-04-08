<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\SendUserCreateNotification;
use App\Models\EmailSetting;
use App\Models\User;
use App\Services\Backend\Settings\Email\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendUserCreateEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param User $user
     * @param EmailSetting $emailSetting
     * @param string $password
     */
    public function __construct(
        public User $user,
        public EmailSetting $emailSetting,
        public string $password,
    ) {
        //
    }

    public $tries = 3; // Maksimum 3 kez dene

    public $backoff = [60, 300, 600]; // 1 dk, 5 dk, 10 dk bekle

    /**
     * @param EmailService $emailService
     *
     * @return void
     */
    public function handle(EmailService $emailService): void
    {
        $emailService->syncMailConfigWithDatabase(emailSetting: $this->emailSetting);

        Mail::to(users: $this->user?->email, name: 'Localkod - Hoşgeldiniz')->send(new SendUserCreateNotification(
            user: $this->user,
            emailSetting: $this->emailSetting,
            password: $this->password
        ));
    }
}
