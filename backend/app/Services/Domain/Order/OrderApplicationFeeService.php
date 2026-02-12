<?php

namespace Ciencia\Services\Domain\Order;

use Ciencia\DomainObjects\Enums\PaymentProviders;
use Ciencia\DomainObjects\Generated\OrderApplicationFeeDomainObjectAbstract;
use Ciencia\DomainObjects\Status\OrderApplicationFeeStatus;
use Ciencia\Helper\Currency;
use Ciencia\Repository\Interfaces\OrderApplicationFeeRepositoryInterface;

class OrderApplicationFeeService
{
    public function __construct(
        private readonly OrderApplicationFeeRepositoryInterface $orderApplicationFeeRepository,
    )
    {
    }

    public function createOrderApplicationFee(
        int                       $orderId,
        int                       $applicationFeeAmountMinorUnit,
        OrderApplicationFeeStatus $orderApplicationFeeStatus,
        PaymentProviders          $paymentMethod,
        string                    $currency,
    ): void
    {
        $isZeroDecimalCurrency = Currency::isZeroDecimalCurrency($currency);

        $applicationFeeAmount = $isZeroDecimalCurrency
            ? $applicationFeeAmountMinorUnit
            : $applicationFeeAmountMinorUnit / 100;

        $this->orderApplicationFeeRepository->create([
            OrderApplicationFeeDomainObjectAbstract::ORDER_ID => $orderId,
            OrderApplicationFeeDomainObjectAbstract::AMOUNT => $applicationFeeAmount,
            OrderApplicationFeeDomainObjectAbstract::STATUS => $orderApplicationFeeStatus->value,
            OrderApplicationFeeDomainObjectAbstract::PAYMENT_METHOD => $paymentMethod->value,
            ORderApplicationFeeDomainObjectAbstract::CURRENCY => $currency,
            OrderApplicationFeeDomainObjectAbstract::PAID_AT => $orderApplicationFeeStatus->value === OrderApplicationFeeStatus::PAID->value
                ? now()->toDateTimeString()
                : null,
        ]);
    }
}
