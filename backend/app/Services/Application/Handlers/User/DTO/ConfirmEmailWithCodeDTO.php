<?php

namespace Ciencia\Services\Application\Handlers\User\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;

class ConfirmEmailWithCodeDTO extends BaseDataObject
{
    public string $code;
    public int $userId;
    public int $accountId;
}
