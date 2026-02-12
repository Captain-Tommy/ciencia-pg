<?php

declare(strict_types=1);

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\Repository\DTO\Organizer\OrganizerStatsResponseDTO;
use Ciencia\Repository\Eloquent\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends BaseRepository<OrganizerDomainObject>
 */
interface OrganizerRepositoryInterface extends RepositoryInterface
{
    public function getOrganizerStats(int $organizerId, int $accountId, string $currencyCode): OrganizerStatsResponseDTO;

    public function getSitemapOrganizers(int $page, int $perPage): LengthAwarePaginator;

    public function getSitemapOrganizerCount(): int;
}
