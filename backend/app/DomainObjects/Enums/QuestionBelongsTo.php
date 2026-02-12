<?php

namespace Ciencia\DomainObjects\Enums;

enum QuestionBelongsTo
{
    use BaseEnum;

    case PRODUCT;
    case ORDER;
}
