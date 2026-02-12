<?php

namespace Ciencia\DomainObjects\Enums;

enum ProductType
{
    use BaseEnum;

    case TICKET;
    case GENERAL;
}
