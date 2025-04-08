<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\SendTestNotification;
use App\Models\EmailSetting;
use App\Services\Backend\Settings\Email\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTestEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param string $toMail
     * @param EmailSetting $emailSetting
     */
    public function __construct(
        public string $toMail,
        public EmailSetting $emailSetting,
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

        Mail::to(users: $this->toMail, name: 'Localkod - Test Mail')->send(new SendTestNotification(emailSetting: $this->emailSetting));
    }
}
