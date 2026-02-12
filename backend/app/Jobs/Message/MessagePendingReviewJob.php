<?php

namespace Ciencia\Jobs\Message;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\MessageDomainObject;
use Ciencia\Mail\Admin\MessagePendingReviewMail;
use Ciencia\Repository\Interfaces\AccountRepositoryInterface;
use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Repository\Interfaces\MessageRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MessagePendingReviewJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        private readonly int   $messageId,
        private readonly array $failures
    )
    {
    }

    public function handle(
        MessageRepositoryInterface $messageRepository,
        EventRepositoryInterface   $eventRepository,
        AccountRepositoryInterface $accountRepository,
        Mailer                     $mailer,
        Repository                 $config
    ): void
    {
        /** @var MessageDomainObject $message */
        $message = $messageRepository->findById($this->messageId);

        /** @var EventDomainObject $event */
        $event = $eventRepository->findById($message->getEventId());

        $account = $accountRepository->findByEventId($event->getId());

        $supportEmail = $config->get('app.platform_support_email');

        if ($supportEmail) {
            $mailer->to($supportEmail)->send(
                new MessagePendingReviewMail($message, $event, $account, $this->failures)
            );
        }
    }
}
