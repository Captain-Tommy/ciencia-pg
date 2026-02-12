<?php

namespace Ciencia\Mail\Event;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\Mail\BaseMail;
use Ciencia\Services\Application\Handlers\Message\DTO\SendMessageDTO;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * @uses /backend/resources/views/emails/event/message.blade.php
 */
class EventMessage extends BaseMail
{
    public function __construct(
        private readonly EventDomainObject $event,
        private readonly EventSettingDomainObject $eventSettings,
        private readonly SendMessageDTO $messageData
    )
    {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: $this->eventSettings->getSupportEmail(),
            subject: $this->messageData->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.event.message',
            with: [
                'messageData' => $this->messageData,
                'event' => $this->event,
                'eventSettings' => $this->eventSettings,
            ]
        );
    }
}
