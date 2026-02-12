<?php

namespace Ciencia\Http\Request\Event;

use Ciencia\DomainObjects\Status\EventStatus;
use Ciencia\Http\Request\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateEventStatusRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(EventStatus::valuesArray())],
        ];
    }
}
