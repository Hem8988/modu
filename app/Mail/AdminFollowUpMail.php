<?php
namespace App\Mail;

use App\Models\FollowUp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminFollowUpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public FollowUp $followUp) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⏳ Follow-up Required: ' . $this->followUp->lead->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_follow_up',
        );
    }
}
