<?php

declare(strict_types=1);

namespace Ciencia\Http\Request\Order;

use Ciencia\Http\Request\BaseRequest;
use Ciencia\Validators\CompleteOrderValidator;

class CompleteOrderRequest extends BaseRequest
{
    public function rules(CompleteOrderValidator $orderValidator): array
    {
        return $orderValidator->rules();
    }

    public function messages(): array
    {
        return app(CompleteOrderValidator::class)->messages();
    }
}
