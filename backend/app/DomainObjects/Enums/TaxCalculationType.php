<?php

namespace Ciencia\DomainObjects\Enums;

enum TaxCalculationType
{
    use BaseEnum;

    case PERCENTAGE;
    case FIXED;
}
