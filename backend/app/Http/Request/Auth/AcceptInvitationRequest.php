<?php

namespace Ciencia\Http\Request\Auth;

use Ciencia\Http\Request\BaseRequest;
use Ciencia\Validators\Rules\RulesHelper;

class AcceptInvitationRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'first_name' => RulesHelper::REQUIRED_STRING,
            'last_name' => RulesHelper::STRING,
            'password' => 'required|string|min:8|confirmed',
            'timezone' => ['required', 'timezone:all'],
        ];
    }
}
