<?php

namespace Ciencia\Services\Application\Handlers\User\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class CancelEmailChangeDTO extends BaseDTO
{
    public function __construct(
        public int $userId,
        public int $accountId,
    )
    {
    }
}
