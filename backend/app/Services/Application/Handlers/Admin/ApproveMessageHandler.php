<?php

declare(strict_types=1);

namespace Ciencia\Services\Application\Handlers\Admin;

use Carbon\Carbon;
use Ciencia\DomainObjects\MessageDomainObject;
use Ciencia\DomainObjects\Status\MessageStatus;
use Ciencia\Exceptions\ResourceNotFoundException;
use Ciencia\Repository\Interfaces\MessageRepositoryInterface;
use Ciencia\Services\Domain\Message\MessageDispatchService;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;

class ApproveMessageHandler
{
    public function __construct(
        private readonly MessageRepositoryInterface $messageRepository,
        private readonly DatabaseManager            $databaseManager,
        private readonly MessageDispatchService     $messageDispatchService,
    )
    {
    }

    public function handle(int $messageId): MessageDomainObject
    {
        return $this->databaseManager->transaction(function () use ($messageId) {
            return $this->approveMessage($messageId);
        });
    }

    private function approveMessage(int $messageId): MessageDomainObject
    {
        $message = $this->messageRepository->findFirst($messageId);

        if ($message === null) {
            throw new ResourceNotFoundException(__('Message not found'));
        }

        if ($message->getStatus() !== MessageStatus::PENDING_REVIEW->name) {
            throw ValidationException::withMessages([
                'status' => [__('Message must be in pending review status to be approved')],
            ]);
        }

        $scheduledAt = $message->getScheduledAt();
        $isFutureScheduled = $scheduledAt !== null && Carbon::parse($scheduledAt)->isFuture();

        if ($isFutureScheduled) {
            return $this->messageRepository->updateFromArray($messageId, [
                'status' => MessageStatus::SCHEDULED->name,
            ]);
        }

        $this->messageDispatchService->dispatchMessage($message, MessageStatus::PENDING_REVIEW);

        return $this->messageRepository->findFirst($messageId);
    }
}
