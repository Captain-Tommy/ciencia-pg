<?php

namespace Ciencia\Services\Domain\SelfService;

use Ciencia\DomainObjects\AttendeeDomainObject;
use Ciencia\DomainObjects\Enums\OrderAuditAction;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\EventSettingDomainObject;
use Ciencia\DomainObjects\InvoiceDomainObject;
use Ciencia\DomainObjects\OrderDomainObject;
use Ciencia\DomainObjects\OrderItemDomainObject;
use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\AttendeeRepositoryInterface;
use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Services\Domain\Attendee\SendAttendeeTicketService;
use Ciencia\Services\Domain\Mail\SendOrderDetailsService;

class SelfServiceResendEmailService
{
    public function __construct(
        private readonly SendAttendeeTicketService $sendAttendeeTicketService,
        private readonly SendOrderDetailsService $sendOrderDetailsService,
        private readonly AttendeeRepositoryInterface $attendeeRepository,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly EventRepositoryInterface $eventRepository,
        private readonly OrderAuditLogService $orderAuditLogService,
    ) {}

    public function resendAttendeeTicket(
        int $attendeeId,
        int $orderId,
        int $eventId,
        string $ipAddress,
        ?string $userAgent
    ): void {
        $attendee = $this->attendeeRepository
            ->loadRelation(new Relationship(OrderDomainObject::class, nested: [
                new Relationship(OrderItemDomainObject::class),
            ], name: 'order'))
            ->findFirstWhere([
                'id' => $attendeeId,
                'order_id' => $orderId,
                'event_id' => $eventId,
            ]);

        $event = $this->eventRepository
            ->loadRelation(new Relationship(OrganizerDomainObject::class, name: 'organizer'))
            ->loadRelation(EventSettingDomainObject::class)
            ->findById($eventId);

        $this->sendAttendeeTicketService->send(
            order: $attendee->getOrder(),
            attendee: $attendee,
            event: $event,
            eventSettings: $event->getEventSettings(),
            organizer: $event->getOrganizer(),
        );

        $this->orderAuditLogService->logEmailResent(
            action: OrderAuditAction::ATTENDEE_EMAIL_RESENT->value,
            eventId: $eventId,
            orderId: $orderId,
            attendeeId: $attendeeId,
            ipAddress: $ipAddress,
            userAgent: $userAgent
        );
    }

    public function resendOrderConfirmation(
        int $orderId,
        int $eventId,
        string $ipAddress,
        ?string $userAgent
    ): void {
        $order = $this->orderRepository
            ->loadRelation(OrderItemDomainObject::class)
            ->loadRelation(AttendeeDomainObject::class)
            ->loadRelation(InvoiceDomainObject::class)
            ->findFirstWhere([
                'id' => $orderId,
                'event_id' => $eventId,
            ]);

        $event = $this->eventRepository
            ->loadRelation(new Relationship(OrganizerDomainObject::class, name: 'organizer'))
            ->loadRelation(new Relationship(EventSettingDomainObject::class))
            ->findById($eventId);

        $this->sendOrderDetailsService->sendCustomerOrderSummary(
            order: $order,
            event: $event,
            organizer: $event->getOrganizer(),
            eventSettings: $event->getEventSettings(),
            invoice: $order->getLatestInvoice()
        );

        $this->orderAuditLogService->logEmailResent(
            action: OrderAuditAction::ORDER_EMAIL_RESENT->value,
            eventId: $eventId,
            orderId: $orderId,
            attendeeId: null,
            ipAddress: $ipAddress,
            userAgent: $userAgent
        );
    }
}
