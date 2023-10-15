<?php

namespace App\Mail;

use App\Models\EmailAddress;
use App\Models\Text;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailTest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public ?EmailAddress $emailAddress = null, public ?Text $text = null)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->text?->subject ?? 'Test-Mail von Bit-Bridge',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $text = $this->text === null ? 'Dies ist eine Test-Mail von Bit-Bridge.' : $this->text->text;
        if ($this->emailAddress) {
            $text = str($text)->replace('{salutation}', $this->emailAddress->salutation);
            $text = str($text)->replace('{receiver}', $this->emailAddress->name);
            $text = str($text)->replace('{myname}', 'MYNAME');
        }

        return new Content(
            text: 'mails.test',
            with: [
                'messageText' => $text,
            ],
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
