<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\OutgoingMessageDomainObject;
use Ciencia\Models\OutgoingMessage;
use Ciencia\Repository\Interfaces\OutgoingMessageRepositoryInterface;

class OutgoingMessageRepository extends BaseRepository implements OutgoingMessageRepositoryInterface
{
    protected function getModel(): string
    {
        return OutgoingMessage::class;
    }

    public function getDomainObject(): string
    {
        return OutgoingMessageDomainObject::class;
    }
}
