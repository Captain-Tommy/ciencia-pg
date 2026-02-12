<?php

namespace Ciencia\DomainObjects\Status;

enum OutgoingMessageStatus
{
    case SENT;
    case FAILED;
}
