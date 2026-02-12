<?php

declare(strict_types=1);

namespace Ciencia\Http\Request\Event;

use Ciencia\Http\Request\BaseRequest;
use Ciencia\Validators\EventRules;

class CreateEventRequest extends BaseRequest
{
    use EventRules;

    public function rules(): array
    {
        return $this->eventRules();
    }

    public function messages(): array
    {
        return $this->eventMessages();
    }
}
