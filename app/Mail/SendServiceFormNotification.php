<?php

declare(strict_types=1);

namespace App\Mail;

use App\DTO\Backend\Forms\ServiceFormDTO;
use App\Models\EmailSetting;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendServiceFormNotification extends Mailable
{
    use Queueable, SerializesModels;

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
                'title'   => 'Localkod - Hizmet Form',
                'url'     => route('frontend.home', ['lang' => session('locale')]),
                'logo'    => asset('backend/assets/media/logos/localkod.png'),
                'content' => view(
                    'email.contact-form.form',
                    [
                        'ip'      => $this->serviceFormDTO->ipAddress,
                        'name'    => $this->serviceFormDTO->name,
                        'email'   => $this->serviceFormDTO->email,
                        'subject' => $this->serviceFormDTO->subject,
                        'phone'   => $this->serviceFormDTO?->phone,
                        'message' => $this->serviceFormDTO->message,
                        'date'    => $this->date,
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
