<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\BlogSubscribe;
use App\Models\EmailSetting;
use App\Models\Section;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SendBlogSubscribeNotification extends Mailable
{
    use Queueable, SerializesModels;

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

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Blog Subscribe Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.default-email',
            with: [
                'title'   => 'Localkod',
                'url'     => route('frontend.home', ['lang' => session('locale')]),
                'logo'    => asset('backend/assets/media/logos/localkod.png'),
                'content' => view(
                    'email.notification.blog',
                    [
                        'contents' => $this->blogContents,
                        'section'  => $this->section,
                    ]
                )->render(),
                'footer' => 'Copyright © ' . now()->year . ' by Localkod',
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
