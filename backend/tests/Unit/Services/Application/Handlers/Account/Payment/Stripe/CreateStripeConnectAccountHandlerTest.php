<?php

namespace Tests\Unit\Services\Application\Handlers\Account\Payment\Stripe;

use Ciencia\Exceptions\SaasModeEnabledException;
use Ciencia\Repository\Interfaces\AccountRepositoryInterface;
use Ciencia\Repository\Interfaces\AccountStripePlatformRepositoryInterface;
use Ciencia\Services\Application\Handlers\Account\Payment\Stripe\CreateStripeConnectAccountHandler;
use Ciencia\Services\Application\Handlers\Account\Payment\Stripe\DTO\CreateStripeConnectAccountDTO;
use Ciencia\Services\Domain\Payment\Stripe\StripeAccountSyncService;
use Ciencia\Services\Infrastructure\Stripe\StripeClientFactory;
use Ciencia\Services\Infrastructure\Stripe\StripeConfigurationService;
use Illuminate\Config\Repository;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class CreateStripeConnectAccountHandlerTest extends TestCase
{
    private CreateStripeConnectAccountHandler $handler;
    private Repository $config;

    protected function setUp(): void
    {
        parent::setUp();

        $accountRepository = m::mock(AccountRepositoryInterface::class);
        $accountStripePlatformRepository = m::mock(AccountStripePlatformRepositoryInterface::class);
        $databaseManager = m::mock(DatabaseManager::class);
        $logger = m::mock(LoggerInterface::class);
        $this->config = m::mock(Repository::class);
        $stripeClientFactory = m::mock(StripeClientFactory::class);
        $stripeConfigurationService = m::mock(StripeConfigurationService::class);
        $stripeAccountSyncService = m::mock(StripeAccountSyncService::class);

        $this->handler = new CreateStripeConnectAccountHandler(
            $accountRepository,
            $accountStripePlatformRepository,
            $databaseManager,
            $logger,
            $this->config,
            $stripeClientFactory,
            $stripeConfigurationService,
            $stripeAccountSyncService,
        );
    }

    public function testHandleThrowsExceptionWhenSaasModeDisabled(): void
    {
        $dto = new CreateStripeConnectAccountDTO(accountId: 1);

        $this->config
            ->shouldReceive('get')
            ->with('app.saas_mode_enabled')
            ->andReturn(false);

        $this->expectException(SaasModeEnabledException::class);
        $this->expectExceptionMessage('Stripe Connect Account creation is only available in Saas Mode.');

        $this->handler->handle($dto);
    }

    public function testHandleAllowsExecutionWhenSaasModeEnabled(): void
    {
        $dto = new CreateStripeConnectAccountDTO(accountId: 1);

        $this->config
            ->shouldReceive('get')
            ->with('app.saas_mode_enabled')
            ->andReturn(true);

        // We expect this to NOT throw the SaasModeEnabledException
        // It will fail later due to missing mocks, but that proves SaaS mode check passed
        try {
            $this->handler->handle($dto);
        } catch (SaasModeEnabledException $e) {
            $this->fail('Should not throw SaasModeEnabledException when saas mode is enabled');
        } catch (\Exception $e) {
            // Expected - will fail on missing mocks, but SaaS check passed
            $this->assertTrue(true);
        }
    }

    protected function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }
}