<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\EmailSetting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendUserCreateNotification extends Mailable
{
    use Queueable, SerializesModels;

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
                'title'       => 'Localkod - Hoşgeldiniz',
                'url'         => route('admin.login.page'),
                'logo'        => asset('backend/assets/media/logos/localkod.png'),
                'content'     => view('email.reset-password.password', ['user' => $this->user, 'password' => $this->password])->render(),
                'button'      => true,
                'buttonUrl'   => route('admin.login.page'),
                'buttonTitle' => 'Giriş Yap',
                'footer'      => 'Copyright © ' . now()->year . ' by Localkod',
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
