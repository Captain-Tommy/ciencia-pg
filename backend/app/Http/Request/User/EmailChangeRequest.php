<?php

namespace Ciencia\Http\Request\User;

use Ciencia\Http\Request\BaseRequest;

class EmailChangeRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'token' => 'required|string',
        ];
    }
}
