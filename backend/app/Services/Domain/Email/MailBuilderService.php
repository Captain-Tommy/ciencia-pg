<?php

namespace Ciencia\Services\Domain\Email;

use Ciencia\DomainObjects\AttendeeDomainObject;
use Ciencia\DomainObjects\Enums\EmailTemplateType;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\InvoiceDomainObject;
use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\Mail\Attendee\AttendeeTicketMail;
use Ciencia\Mail\Order\OrderSummary;
use Ciencia\Services\Domain\Email\DTO\RenderedEmailTemplateDTO;

class MailBuilderService
{
    public function __construct(
        private readonly EmailTemplateService $emailTemplateService,
        private readonly EmailTokenContextBuilder $tokenContextBuilder,
    ) {
    }

    public function buildAttendeeTicketMail(
        AttendeeDomainObject $attendee,
        OrderDomainObject $order,
        EventDomainObject $event,
        EventSettingDomainObject $eventSettings,
        OrganizerDomainObject $organizer
    ): AttendeeTicketMail {
        $renderedTemplate = $this->renderAttendeeTicketTemplate(
            $attendee,
            $order,
            $event,
            $eventSettings,
            $organizer
        );

        return new AttendeeTicketMail(
            order: $order,
            attendee: $attendee,
            event: $event,
            eventSettings: $eventSettings,
            organizer: $organizer,
            renderedTemplate: $renderedTemplate,
        );
    }

    public function buildOrderSummaryMail(
        OrderDomainObject $order,
        EventDomainObject $event,
        EventSettingDomainObject $eventSettings,
        OrganizerDomainObject $organizer,
        ?InvoiceDomainObject $invoice = null
    ): OrderSummary {
        $renderedTemplate = $this->renderOrderSummaryTemplate(
            $order,
            $event,
            $eventSettings,
            $organizer
        );

        return new OrderSummary(
            order: $order,
            event: $event,
            organizer: $organizer,
            eventSettings: $eventSettings,
            invoice: $invoice,
            renderedTemplate: $renderedTemplate,
        );
    }

    private function renderAttendeeTicketTemplate(
        AttendeeDomainObject $attendee,
        OrderDomainObject $order,
        EventDomainObject $event,
        EventSettingDomainObject $eventSettings,
        OrganizerDomainObject $organizer
    ): ?RenderedEmailTemplateDTO {
        $template = $this->emailTemplateService->getTemplateByType(
            type: EmailTemplateType::ATTENDEE_TICKET,
            accountId: $event->getAccountId(),
            eventId: $event->getId(),
            organizerId: $organizer->getId()
        );

        if (!$template) {
            return null;
        }

        $context = $this->tokenContextBuilder->buildAttendeeTicketContext(
            $attendee,
            $order,
            $event,
            $organizer,
            $eventSettings
        );

        return $this->emailTemplateService->renderTemplate($template, $context);
    }

    private function renderOrderSummaryTemplate(
        OrderDomainObject $order,
        EventDomainObject $event,
        EventSettingDomainObject $eventSettings,
        OrganizerDomainObject $organizer
    ): ?RenderedEmailTemplateDTO {
        $template = $this->emailTemplateService->getTemplateByType(
            type: EmailTemplateType::ORDER_CONFIRMATION,
            accountId: $event->getAccountId(),
            eventId: $event->getId(),
            organizerId: $organizer->getId()
        );

        if (!$template) {
            return null;
        }

        $context = $this->tokenContextBuilder->buildOrderConfirmationContext(
            $order,
            $event,
            $organizer,
            $eventSettings
        );

        return $this->emailTemplateService->renderTemplate($template, $context);
    }
}
