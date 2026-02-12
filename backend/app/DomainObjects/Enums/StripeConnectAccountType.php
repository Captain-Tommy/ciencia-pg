<?php

namespace Ciencia\DomainObjects\Enums;

enum StripeConnectAccountType: string
{
    use BaseEnum;

    case STANDARD = 'standard';
    case EXPRESS = 'express';
}
