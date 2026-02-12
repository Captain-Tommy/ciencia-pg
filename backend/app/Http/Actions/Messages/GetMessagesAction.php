<?php

namespace Ciencia\Http\Actions\Messages;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\MessageDomainObject;
use Ciencia\DomainObjects\UserDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\MessageRepositoryInterface;
use Ciencia\Resources\Message\MessageResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetMessagesAction extends BaseAction
{
    private MessageRepositoryInterface $messageRepository;

    public function __construct(MessageRepositoryInterface $MessageRepository)
    {
        $this->messageRepository = $MessageRepository;
    }

    public function __invoke(Request $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $messages = $this->messageRepository
            ->loadRelation(new Relationship(UserDomainObject::class, name: 'sent_by_user'))
            ->findByEventId($eventId, QueryParamsDTO::fromArray($request->query->all()));

        return $this->filterableResourceResponse(
            resource: MessageResource::class,
            data: $messages,
            domainObject: MessageDomainObject::class
        );
    }
}
