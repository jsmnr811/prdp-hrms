<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TemporaryPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $temporaryPassword;

    public $employeeNumber;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $temporaryPassword, string $employeeNumber = null)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
        $this->employeeNumber = $employeeNumber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Password Has Been Reset',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.temporary_password',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
