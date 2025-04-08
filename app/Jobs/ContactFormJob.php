<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DTO\Backend\Forms\ContactFormDTO;
use App\Mail\SendContactFormNotification;
use App\Models\EmailSetting;
use App\Services\Backend\Settings\Email\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ContactFormJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param EmailSetting $emailSetting
     * @param ContactFormDTO $contactFormDTO
     * @param string $date
     */
    public function __construct(
        public EmailSetting $emailSetting,
        public ContactFormDTO $contactFormDTO,
        public string $date,
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

        Mail::to(users: $this->emailSetting?->notification_email, name: 'Localkod - İletişim Formu')->send(new SendContactFormNotification(
            emailSetting: $this->emailSetting,
            contactFormDTO: $this->contactFormDTO,
            date: $this->date,
        ));
    }
}
