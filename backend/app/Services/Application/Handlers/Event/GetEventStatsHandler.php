<?php

namespace Ciencia\Services\Application\Handlers\Event;

use Ciencia\Services\Application\Handlers\Event\DTO\EventStatsRequestDTO;
use Ciencia\Services\Application\Handlers\Event\DTO\EventStatsResponseDTO;
use Ciencia\Services\Domain\Event\EventStatsFetchService;

readonly class GetEventStatsHandler
{
    public function __construct(private EventStatsFetchService $eventStatsFetchService)
    {
    }

    public function handle(EventStatsRequestDTO $statsRequestDTO): EventStatsResponseDTO
    {
        return $this->eventStatsFetchService->getEventStats($statsRequestDTO);
    }
}
