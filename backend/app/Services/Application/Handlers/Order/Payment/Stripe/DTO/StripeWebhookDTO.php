<?php

namespace Ciencia\Services\Application\Handlers\Order\Payment\Stripe\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class StripeWebhookDTO extends BaseDTO
{
    public function __construct(
        public readonly string $headerSignature,
        public readonly string $payload,
    )
    {
    }
}
