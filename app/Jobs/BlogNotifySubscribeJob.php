<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\SendBlogSubscribeNotification;
use App\Models\BlogSubscribe;
use App\Models\EmailSetting;
use App\Models\Section;
use App\Services\Backend\Settings\Email\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class BlogNotifySubscribeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param Collection $blogContents
     * @param BlogSubscribe $blogSubscribe
     * @param EmailSetting $emailSetting
     * @param Section|null $section
     */
    public function __construct(
        public Collection $blogContents,
        public BlogSubscribe $blogSubscribe,
        public EmailSetting $emailSetting,
        public ?Section $section,
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

        Mail::to(users: $this->blogSubscribe->email, name: env('APP_NAME'))->send(new SendBlogSubscribeNotification(
            blogContents: $this->blogContents,
            blogSubscribe: $this->blogSubscribe,
            emailSetting: $this->emailSetting,
            section: $this->section,
        ));
    }
}
