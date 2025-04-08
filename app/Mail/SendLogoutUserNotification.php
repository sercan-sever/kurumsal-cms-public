<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\EmailSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendLogoutUserNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param User $user
     * @param string $ip
     * @param string $date
     * @param EmailSetting $emailSetting
     */
    public function __construct(
        public User $user,
        public string $ip,
        public string $date,
        public EmailSetting $emailSetting
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Çıkış Yapan Kullanıcı Bildirimi',
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
                'title'   => 'Localkod - Çıkış Yapan Kullanıcı',
                'logo'    => asset('backend/assets/media/logos/localkod.png'),
                'content' => view(
                    'email.login-logout.login-logout',
                    [
                        'ip'         => $this->ip,
                        'name'       => $this->user->name,
                        'email'      => $this->user->email,
                        'logoutDate' => $this->date,
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
