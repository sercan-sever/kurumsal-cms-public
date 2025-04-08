<?php

declare(strict_types=1);

namespace App\Mail;

use App\DTO\Backend\Forms\ReferenceFormDTO;
use App\Models\EmailSetting;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendReferenceFormNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param EmailSetting $emailSetting
     * @param ReferenceFormDTO $referenceFormDTO
     * @param string $date
     */
    public function __construct(
        public EmailSetting $emailSetting,
        public ReferenceFormDTO $referenceFormDTO,
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
                'title'   => 'Localkod - Referans Form',
                'url'     => route('frontend.home', ['lang' => session('locale')]),
                'logo'    => asset('backend/assets/media/logos/localkod.png'),
                'content' => view(
                    'email.contact-form.form',
                    [
                        'ip'      => $this->referenceFormDTO->ipAddress,
                        'name'    => $this->referenceFormDTO->name,
                        'email'   => $this->referenceFormDTO->email,
                        'subject' => $this->referenceFormDTO->subject,
                        'phone'   => $this->referenceFormDTO?->phone,
                        'message' => $this->referenceFormDTO->message,
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
