<?php

namespace Ciencia\Http\Request\Organizer;

use Ciencia\DomainObjects\Status\OrganizerStatus;
use Ciencia\Http\Request\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizerStatusRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(OrganizerStatus::valuesArray())],
        ];
    }
}