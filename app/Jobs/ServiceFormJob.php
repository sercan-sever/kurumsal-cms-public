<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DTO\Backend\Forms\ServiceFormDTO;
use App\Mail\SendServiceFormNotification;
use App\Models\EmailSetting;
use App\Services\Backend\Settings\Email\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ServiceFormJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param EmailSetting $emailSetting
     * @param ServiceFormDTO $serviceFormDTO
     * @param string $date
     */
    public function __construct(
        public EmailSetting $emailSetting,
        public ServiceFormDTO $serviceFormDTO,
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

        Mail::to(users: $this->emailSetting?->notification_email, name: 'Localkod - Hizmet Form')->send(new SendServiceFormNotification(
            emailSetting: $this->emailSetting,
            serviceFormDTO: $this->serviceFormDTO,
            date: $this->date,
        ));
    }
}
