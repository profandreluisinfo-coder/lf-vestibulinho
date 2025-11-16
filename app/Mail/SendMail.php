<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $view;
    public $attachment; // Agora é uma string ou null

    /**
     * Cria uma nova instância da classe.
     *
     * @param string $subject
     * @param array $content
     * @param string $view
     * @param string|null $attachment Caminho do arquivo
     */
    public function __construct(string $subject, array $content, string $view, ?string $attachment = null)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->view = $view;
        $this->attachment = $attachment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->view,
            with: $this->content
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        if ($this->attachment) {
            return [
                Attachment::fromPath($this->attachment)
                    ->as(basename($this->attachment))
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}