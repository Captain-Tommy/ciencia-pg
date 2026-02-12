<?php

namespace Ciencia\Services\Application\Handlers\Admin;

use Ciencia\DomainObjects\Status\AttendeeStatus;
use Ciencia\DomainObjects\Status\EventStatus;
use Ciencia\Repository\Interfaces\AccountRepositoryInterface;
use Ciencia\Repository\Interfaces\AttendeeRepositoryInterface;
use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Repository\Interfaces\UserRepositoryInterface;
use Ciencia\Services\Application\Handlers\Admin\DTO\GetAdminStatsDTO;

class GetAdminStatsHandler
{
    public function __construct(
        private readonly UserRepositoryInterface     $userRepository,
        private readonly AccountRepositoryInterface  $accountRepository,
        private readonly EventRepositoryInterface    $eventRepository,
        private readonly AttendeeRepositoryInterface $attendeeRepository,
    )
    {
    }

    public function handle(): GetAdminStatsDTO
    {
        $totalUsers = $this->userRepository->countWhere([]);
        $totalAccounts = $this->accountRepository->countWhere([]);
        $totalLiveEvents = $this->eventRepository->countWhere(['status' => EventStatus::LIVE->name]);
        $totalTicketsSold = $this->attendeeRepository->countWhere(['status' => AttendeeStatus::ACTIVE->name]);

        return new GetAdminStatsDTO(
            total_users: $totalUsers,
            total_accounts: $totalAccounts,
            total_live_events: $totalLiveEvents,
            total_tickets_sold: $totalTicketsSold,
        );
    }
}
