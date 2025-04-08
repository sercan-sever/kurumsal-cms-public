<?php

namespace App\Console\Commands;

use App\Services\Frontend\Notification\BlogNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyBlogSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:subscribers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Blog Subscribe Notification';

    /**
     * Execute the console command.
     */
    public function handle(BlogNotificationService $blogNotificationService)
    {
        try {
            $blogNotificationService->notifySubscribers();

            $this->info('Blog Subscribe Notification');
        } catch (\Exception $exception) {
            Log::error(message: 'NotifyBlogSubscribers ( handle ) Bir Hata Oluştu !!!', context: [$exception->getMessage()]);
        }
    }
}
