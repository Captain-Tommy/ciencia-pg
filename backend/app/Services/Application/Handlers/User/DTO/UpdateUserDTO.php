<?php

namespace Ciencia\Services\Application\Handlers\User\DTO;

use Ciencia\DataTransferObjects\BaseDTO;
use Ciencia\DomainObjects\Enums\Role;
use Ciencia\DomainObjects\Status\UserStatus;

class UpdateUserDTO extends BaseDTO
{
    public function __construct(
        public readonly int        $id,
        public readonly int        $account_id,
        public readonly string     $first_name,
        public readonly string     $last_name,
        public readonly Role       $role,
        public readonly UserStatus $status,
        public readonly int        $updated_by_user_id,
    )
    {
    }
}
