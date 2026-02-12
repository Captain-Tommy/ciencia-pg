<?php

namespace Ciencia\Services\Application\Handlers\Admin;

use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Services\Application\Handlers\Admin\DTO\GetAllEventsDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetAllEventsHandler
{
    public function __construct(
        private readonly EventRepositoryInterface $eventRepository,
    )
    {
    }

    public function handle(GetAllEventsDTO $dto): LengthAwarePaginator
    {
        return $this->eventRepository->getAllEventsForAdmin(
            search: $dto->search,
            perPage: $dto->perPage,
            sortBy: $dto->sortBy,
            sortDirection: $dto->sortDirection,
        );
    }
}
