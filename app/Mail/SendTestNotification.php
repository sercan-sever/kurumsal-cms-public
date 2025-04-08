<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\EmailSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendTestNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param EmailSetting $emailSetting
     */
    public function __construct(public EmailSetting $emailSetting)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSetting?->subject ?? '',
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
                'title'   => 'Localkod - Test Mail',
                'url'     => route('frontend.home', ['lang' => session('locale')]),
                'logo'    => asset('backend/assets/media/logos/localkod.png'),
                'content' => 'Bu Bir Test Mesajıdır.',
                'button'  => true,
                'buttonUrl'   => route('frontend.home', ['lang' => session('locale')]),
                'footer'  => 'Copyright © ' . now()->year . ' by Localkod',
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
