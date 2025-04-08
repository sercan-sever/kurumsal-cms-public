<?php

declare(strict_types=1);

namespace App\Services\Frontend\Notification;

use App\Enums\Defaults\StatusEnum;
use App\Jobs\BlogNotifySubscribeJob;
use App\Models\Blog;
use App\Models\EmailSetting;
use App\Services\Backend\Blogs\Subscribe\BlogSubscribeService;
use App\Services\Backend\Sections\SectionService;
use App\Services\Backend\Settings\Email\EmailService;
use App\Services\Frontend\Blogs\BlogService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class BlogNotificationService
{
    /**
     * @param BlogService $blogService
     * @param SectionService $sectionService
     * @param BlogSubscribeService $subscribeService
     * @param EmailService $emailService
     *
     * @return void
     */
    public function __construct(
        private readonly BlogService $blogService,
        private readonly SectionService $sectionService,
        private readonly BlogSubscribeService $subscribeService,
        private readonly EmailService $emailService,
    ) {
        //
    }


    /**
     * @return bool
     */
    public function notifySubscribers(): bool
    {
        try {
            $emailSetting = $this->emailService->getModel();
            if (!$this->emailService->syncMailConfigWithDatabaseCheck(emailSetting: $emailSetting)) {
                return false;
            }

            $blogContents = $this->blogService->getAllPublishedBlogContent();
            if ($blogContents->isEmpty()) {
                return false;
            }

            $subscribers  = $this->subscribeService->getAllActiveModel();
            if ($subscribers->isEmpty()) {
                return $this->blogNotifiedChange(blogContents: $blogContents);
            }

            $blogContentGroups = $blogContents->groupBy('language_id')->collect();
            $subscribeGroups   = $subscribers->groupBy('language_id')->collect();
            $section           = $this->sectionService->getBlogSection();

            $blogContentGroups->each(function ($blogGroup, $languageId) use ($subscribeGroups, $emailSetting, $section) {
                if ($subscribeGroups->has($languageId)) {
                    $subscribersForLanguage = $subscribeGroups->get($languageId);

                    if ($blogGroup->isNotEmpty()) {
                        $subscribersForLanguage->each(function ($subscriber) use ($blogGroup, $emailSetting, $section) {
                            // Log::info("Job Dispatch Edildi - Abone ID: {$subscriber->id}, Blog Sayısı: " . $blogGroup->count());

                            BlogNotifySubscribeJob::dispatch(
                                blogContents: $blogGroup,
                                blogSubscribe: $subscriber,
                                emailSetting: $emailSetting,
                                section: $section,
                            );
                        });
                    }
                }
            });

            return $this->blogNotifiedChange(blogContents: $blogContents);
        } catch (\Exception $exception) {
            Log::error("BlogNotificationService ( notifySubscribers ) : ", context: [$exception->getMessage()]);

            return false;
        }
    }

    /**
     * @param Collection $blogContents
     *
     * @return bool
     */
    private function blogNotifiedChange(Collection $blogContents): bool
    {
        try {
            $pageIds = $blogContents->pluck('blog_id'); // İlişkili content ID'lerini al
            $result = Blog::query()->whereIn('id', $pageIds)->update(['notified_at' => StatusEnum::ACTIVE]);

            return (bool)$result;
        } catch (\Exception $exception) {
            Log::error("BlogNotificationService ( blogNotifiedChange ) : ", context: [$exception->getMessage()]);

            return false;
        }
    }
}
