<?php

declare(strict_types=1);

namespace Ciencia\Http\Request\Event;

use Ciencia\Http\Request\BaseRequest;
use Ciencia\Validators\EventRules;

class UpdateEventRequest extends BaseRequest
{
    use EventRules;

    public function rules(): array
    {
        $rules =  $this->eventRules();
        unset($rules['organizer_id']);

        return $rules;
    }

    public function messages(): array
    {
        return $this->eventMessages();
    }
}
