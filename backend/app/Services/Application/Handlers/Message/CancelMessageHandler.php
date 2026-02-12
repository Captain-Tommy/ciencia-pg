<?php

declare(strict_types=1);

namespace Ciencia\Services\Application\Handlers\Message;

use Ciencia\DomainObjects\MessageDomainObject;
use Ciencia\DomainObjects\Status\MessageStatus;
use Ciencia\Exceptions\ResourceNotFoundException;
use Ciencia\Repository\Interfaces\MessageRepositoryInterface;
use Illuminate\Validation\ValidationException;

class CancelMessageHandler
{
    public function __construct(
        private readonly MessageRepositoryInterface $messageRepository,
    )
    {
    }

    public function handle(int $messageId, int $eventId): MessageDomainObject
    {
        $message = $this->messageRepository->findFirstWhere([
            'id' => $messageId,
            'event_id' => $eventId,
        ]);

        if ($message === null) {
            throw new ResourceNotFoundException(__('Message not found'));
        }

        if ($message->getStatus() !== MessageStatus::SCHEDULED->name) {
            throw ValidationException::withMessages([
                'status' => [__('Only scheduled messages can be cancelled')],
            ]);
        }

        $updated = $this->messageRepository->updateWhere(
            ['status' => MessageStatus::CANCELLED->name],
            ['id' => $messageId, 'status' => MessageStatus::SCHEDULED->name],
        );

        if ($updated === 0) {
            throw ValidationException::withMessages([
                'status' => [__('This message can no longer be cancelled')],
            ]);
        }

        return $this->messageRepository->findFirst($messageId);
    }
}
