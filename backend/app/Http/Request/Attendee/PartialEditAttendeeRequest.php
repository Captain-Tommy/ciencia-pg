<?php

namespace Ciencia\Http\Request\Attendee;

use Ciencia\DomainObjects\Status\AttendeeStatus;
use Ciencia\Http\Request\BaseRequest;
use Ciencia\Validators\Rules\InsensitiveIn;

class PartialEditAttendeeRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'status' => ['sometimes', new InsensitiveIn(AttendeeStatus::valuesArray())],
            'first_name' => ['sometimes', 'string', 'max:100', 'min:1'],
            'last_name' => ['sometimes', 'string', 'max:100', 'min:1'],
            'email' => ['sometimes', 'email', 'max:100'],
        ];
    }
}
