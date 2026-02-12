<?php

declare(strict_types=1);

namespace Ciencia\Http\Request\Order;

use Ciencia\Http\Request\BaseRequest;
use Ciencia\Services\Domain\Order\OrderCreateRequestValidationService;

class CreateOrderRequest extends BaseRequest
{
    /**
     * @see OrderCreateRequestValidationService
     */
    public function rules(): array
    {
        return [];
    }
}
