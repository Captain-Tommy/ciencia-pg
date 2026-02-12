<?php

declare(strict_types=1);

namespace Ciencia\Providers;

use Ciencia\Repository\Eloquent\AccountAttributionRepository;
use Ciencia\Repository\Eloquent\AccountConfigurationRepository;
use Ciencia\Repository\Eloquent\AccountMessagingTierRepository;
use Ciencia\Repository\Eloquent\AccountRepository;
use Ciencia\Repository\Eloquent\AccountStripePlatformRepository;
use Ciencia\Repository\Eloquent\AccountUserRepository;
use Ciencia\Repository\Eloquent\AccountVatSettingRepository;
use Ciencia\Repository\Eloquent\AffiliateRepository;
use Ciencia\Repository\Eloquent\AttendeeCheckInRepository;
use Ciencia\Repository\Eloquent\AttendeeRepository;
use Ciencia\Repository\Eloquent\CapacityAssignmentRepository;
use Ciencia\Repository\Eloquent\CheckInListRepository;
use Ciencia\Repository\Eloquent\EmailTemplateRepository;
use Ciencia\Repository\Eloquent\EventDailyStatisticRepository;
use Ciencia\Repository\Eloquent\EventRepository;
use Ciencia\Repository\Eloquent\EventSettingsRepository;
use Ciencia\Repository\Eloquent\EventStatisticRepository;
use Ciencia\Repository\Eloquent\ImageRepository;
use Ciencia\Repository\Eloquent\InvoiceRepository;
use Ciencia\Repository\Eloquent\MessageRepository;
use Ciencia\Repository\Eloquent\OrderApplicationFeeRepository;
use Ciencia\Repository\Eloquent\OrderAuditLogRepository;
use Ciencia\Repository\Eloquent\OrderItemRepository;
use Ciencia\Repository\Eloquent\OrderPaymentPlatformFeeRepository;
use Ciencia\Repository\Eloquent\OrderRefundRepository;
use Ciencia\Repository\Eloquent\OrderRepository;
use Ciencia\Repository\Eloquent\OrganizerRepository;
use Ciencia\Repository\Eloquent\OrganizerSettingsRepository;
use Ciencia\Repository\Eloquent\OutgoingMessageRepository;
use Ciencia\Repository\Eloquent\PasswordResetRepository;
use Ciencia\Repository\Eloquent\PasswordResetTokenRepository;
use Ciencia\Repository\Eloquent\ProductCategoryRepository;
use Ciencia\Repository\Eloquent\ProductPriceRepository;
use Ciencia\Repository\Eloquent\ProductRepository;
use Ciencia\Repository\Eloquent\PromoCodeRepository;
use Ciencia\Repository\Eloquent\QuestionAndAnswerViewRepository;
use Ciencia\Repository\Eloquent\QuestionAnswerRepository;
use Ciencia\Repository\Eloquent\QuestionRepository;
use Ciencia\Repository\Eloquent\StripeCustomerRepository;
use Ciencia\Repository\Eloquent\StripePaymentsRepository;
use Ciencia\Repository\Eloquent\StripePayoutsRepository;
use Ciencia\Repository\Eloquent\TaxAndFeeRepository;
use Ciencia\Repository\Eloquent\TicketLookupTokenRepository;
use Ciencia\Repository\Eloquent\UserRepository;
use Ciencia\Repository\Eloquent\WebhookLogRepository;
use Ciencia\Repository\Eloquent\WebhookRepository;
use Ciencia\Repository\Interfaces\AccountAttributionRepositoryInterface;
use Ciencia\Repository\Interfaces\AccountConfigurationRepositoryInterface;
use Ciencia\Repository\Interfaces\AccountMessagingTierRepositoryInterface;
use Ciencia\Repository\Interfaces\AccountRepositoryInterface;
use Ciencia\Repository\Interfaces\AccountStripePlatformRepositoryInterface;
use Ciencia\Repository\Interfaces\AccountUserRepositoryInterface;
use Ciencia\Repository\Interfaces\AccountVatSettingRepositoryInterface;
use Ciencia\Repository\Interfaces\AffiliateRepositoryInterface;
use Ciencia\Repository\Interfaces\AttendeeCheckInRepositoryInterface;
use Ciencia\Repository\Interfaces\AttendeeRepositoryInterface;
use Ciencia\Repository\Interfaces\CapacityAssignmentRepositoryInterface;
use Ciencia\Repository\Interfaces\CheckInListRepositoryInterface;
use Ciencia\Repository\Interfaces\EmailTemplateRepositoryInterface;
use Ciencia\Repository\Interfaces\EventDailyStatisticRepositoryInterface;
use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Repository\Interfaces\EventSettingsRepositoryInterface;
use Ciencia\Repository\Interfaces\EventStatisticRepositoryInterface;
use Ciencia\Repository\Interfaces\ImageRepositoryInterface;
use Ciencia\Repository\Interfaces\InvoiceRepositoryInterface;
use Ciencia\Repository\Interfaces\MessageRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderApplicationFeeRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderAuditLogRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderItemRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderPaymentPlatformFeeRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderRefundRepositoryInterface;
use Ciencia\Repository\Interfaces\OrderRepositoryInterface;
use Ciencia\Repository\Interfaces\OrganizerRepositoryInterface;
use Ciencia\Repository\Interfaces\OrganizerSettingsRepositoryInterface;
use Ciencia\Repository\Interfaces\OutgoingMessageRepositoryInterface;
use Ciencia\Repository\Interfaces\PasswordResetRepositoryInterface;
use Ciencia\Repository\Interfaces\PasswordResetTokenRepositoryInterface;
use Ciencia\Repository\Interfaces\ProductCategoryRepositoryInterface;
use Ciencia\Repository\Interfaces\ProductPriceRepositoryInterface;
use Ciencia\Repository\Interfaces\ProductRepositoryInterface;
use Ciencia\Repository\Interfaces\PromoCodeRepositoryInterface;
use Ciencia\Repository\Interfaces\QuestionAndAnswerViewRepositoryInterface;
use Ciencia\Repository\Interfaces\QuestionAnswerRepositoryInterface;
use Ciencia\Repository\Interfaces\QuestionRepositoryInterface;
use Ciencia\Repository\Interfaces\StripeCustomerRepositoryInterface;
use Ciencia\Repository\Interfaces\StripePaymentsRepositoryInterface;
use Ciencia\Repository\Interfaces\StripePayoutsRepositoryInterface;
use Ciencia\Repository\Interfaces\TaxAndFeeRepositoryInterface;
use Ciencia\Repository\Interfaces\TicketLookupTokenRepositoryInterface;
use Ciencia\Repository\Interfaces\UserRepositoryInterface;
use Ciencia\Repository\Interfaces\WebhookLogRepositoryInterface;
use Ciencia\Repository\Interfaces\WebhookRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @todo - find a way to auto-bind these
     */
    private static array $interfaceToConcreteMap = [
        UserRepositoryInterface::class => UserRepository::class,
        AccountRepositoryInterface::class => AccountRepository::class,
        AccountAttributionRepositoryInterface::class => AccountAttributionRepository::class,
        EventRepositoryInterface::class => EventRepository::class,
        ProductRepositoryInterface::class => ProductRepository::class,
        OrderRepositoryInterface::class => OrderRepository::class,
        AttendeeRepositoryInterface::class => AttendeeRepository::class,
        AffiliateRepositoryInterface::class => AffiliateRepository::class,
        OrderItemRepositoryInterface::class => OrderItemRepository::class,
        QuestionRepositoryInterface::class => QuestionRepository::class,
        QuestionAnswerRepositoryInterface::class => QuestionAnswerRepository::class,
        StripePaymentsRepositoryInterface::class => StripePaymentsRepository::class,
        PromoCodeRepositoryInterface::class => PromoCodeRepository::class,
        MessageRepositoryInterface::class => MessageRepository::class,
        PasswordResetTokenRepositoryInterface::class => PasswordResetTokenRepository::class,
        PasswordResetRepositoryInterface::class => PasswordResetRepository::class,
        TaxAndFeeRepositoryInterface::class => TaxAndFeeRepository::class,
        ImageRepositoryInterface::class => ImageRepository::class,
        ProductPriceRepositoryInterface::class => ProductPriceRepository::class,
        EventStatisticRepositoryInterface::class => EventStatisticRepository::class,
        EventDailyStatisticRepositoryInterface::class => EventDailyStatisticRepository::class,
        EventSettingsRepositoryInterface::class => EventSettingsRepository::class,
        OrganizerRepositoryInterface::class => OrganizerRepository::class,
        AccountUserRepositoryInterface::class => AccountUserRepository::class,
        CapacityAssignmentRepositoryInterface::class => CapacityAssignmentRepository::class,
        StripeCustomerRepositoryInterface::class => StripeCustomerRepository::class,
        CheckInListRepositoryInterface::class => CheckInListRepository::class,
        AttendeeCheckInRepositoryInterface::class => AttendeeCheckInRepository::class,
        ProductCategoryRepositoryInterface::class => ProductCategoryRepository::class,
        InvoiceRepositoryInterface::class => InvoiceRepository::class,
        OrderRefundRepositoryInterface::class => OrderRefundRepository::class,
        WebhookRepositoryInterface::class => WebhookRepository::class,
        WebhookLogRepositoryInterface::class => WebhookLogRepository::class,
        OrderApplicationFeeRepositoryInterface::class => OrderApplicationFeeRepository::class,
        OrderAuditLogRepositoryInterface::class => OrderAuditLogRepository::class,
        OrderPaymentPlatformFeeRepositoryInterface::class => OrderPaymentPlatformFeeRepository::class,
        StripePayoutsRepositoryInterface::class => StripePayoutsRepository::class,
        AccountConfigurationRepositoryInterface::class => AccountConfigurationRepository::class,
        QuestionAndAnswerViewRepositoryInterface::class => QuestionAndAnswerViewRepository::class,
        OutgoingMessageRepositoryInterface::class => OutgoingMessageRepository::class,
        OrganizerSettingsRepositoryInterface::class => OrganizerSettingsRepository::class,
        EmailTemplateRepositoryInterface::class => EmailTemplateRepository::class,
        AccountStripePlatformRepositoryInterface::class => AccountStripePlatformRepository::class,
        AccountVatSettingRepositoryInterface::class => AccountVatSettingRepository::class,
        TicketLookupTokenRepositoryInterface::class => TicketLookupTokenRepository::class,
        AccountMessagingTierRepositoryInterface::class => AccountMessagingTierRepository::class,
    ];

    public function register(): void
    {
        foreach (self::$interfaceToConcreteMap as $interface => $concrete) {
            $this->app->bind($interface, $concrete);
        }
    }
}
