<?php

namespace Ciencia\Services\Domain\Attendee;

use Ciencia\DomainObjects\AttendeeDomainObject;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\Services\Domain\Email\MailBuilderService;
use Illuminate\Contracts\Mail\Mailer;

class SendAttendeeTicketService
{
    public function __construct(
        private readonly Mailer             $mailer,
        private readonly MailBuilderService $mailBuilderService,
    )
    {
    }

    public function send(
        OrderDomainObject        $order,
        AttendeeDomainObject     $attendee,
        EventDomainObject        $event,
        EventSettingDomainObject $eventSettings,
        OrganizerDomainObject    $organizer,
    ): void
    {
        $mail = $this->mailBuilderService->buildAttendeeTicketMail(
            $attendee,
            $order,
            $event,
            $eventSettings,
            $organizer
        );

        $this->mailer
            ->to($attendee->getEmail())
            ->locale($attendee->getLocale())
            ->send($mail);
    }
}
