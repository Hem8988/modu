<?php
namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚨 New Lead Alert: ' . $this->lead->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_lead',
        );
    }
}
