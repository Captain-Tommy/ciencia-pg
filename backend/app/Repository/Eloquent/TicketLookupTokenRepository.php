<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\TicketLookupTokenDomainObject;
use Ciencia\Models\TicketLookupToken;
use Ciencia\Repository\Interfaces\TicketLookupTokenRepositoryInterface;

class TicketLookupTokenRepository extends BaseRepository implements TicketLookupTokenRepositoryInterface
{
    protected function getModel(): string
    {
        return TicketLookupToken::class;
    }

    public function getDomainObject(): string
    {
        return TicketLookupTokenDomainObject::class;
    }
}
