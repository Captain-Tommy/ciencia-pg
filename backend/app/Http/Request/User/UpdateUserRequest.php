<?php

namespace Ciencia\Http\Request\User;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\DomainObjects\Status\UserStatus;
use Ciencia\Http\Request\BaseRequest;
use Ciencia\Validators\Rules\RulesHelper;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'first_name' => RulesHelper::STRING,
            'last_name' => RulesHelper::STRING,
            'status' => Rule::in([UserStatus::INACTIVE->name, UserStatus::ACTIVE->name]), // don't allow INVITED
            'role' => Rule::in(Role::getAssignableRoles())
        ];
    }
}
