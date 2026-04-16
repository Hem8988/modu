<?php
namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserAppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Confirmed: Site Visit for ' . ($this->appointment->lead->name ?? 'ModuShade Project'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user_appointment',
        );
    }
}
