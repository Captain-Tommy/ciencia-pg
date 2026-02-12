<?php

declare(strict_types=1);

namespace Ciencia\Http\Request\User;

use Ciencia\DomainObjects\Enums\Role;
use Ciencia\Http\Request\BaseRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'required|min:1',
            'last_name' => 'min:1|nullable',
            'role' => ['required', Rule::in(Role::getAssignableRoles())],
            'email' => [
                'required',
                'email',
            ],
        ];
    }
}
