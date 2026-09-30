<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $sport;
    public $date;
    public $time;
    public $court;

    public function __construct($userName, $sport, $date, $time, $court)
    {
        $this->userName = $userName;
        $this->sport = $sport;
        $this->date = $date;
        $this->time = $time;
        $this->court = $court;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Friendly Reminder: Upcoming Reservation in 1 Hour',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation_reminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
