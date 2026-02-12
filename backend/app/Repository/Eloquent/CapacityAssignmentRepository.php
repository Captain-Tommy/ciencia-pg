<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\CapacityAssignmentDomainObject;
use Ciencia\DomainObjects\Generated\CapacityAssignmentDomainObjectAbstract;
use Ciencia\Http\DTO\QueryParamsDTO;
use Ciencia\Models\CapacityAssignment;
use Ciencia\Repository\Interfaces\CapacityAssignmentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class CapacityAssignmentRepository extends BaseRepository implements CapacityAssignmentRepositoryInterface
{
    protected function getModel(): string
    {
        return CapacityAssignment::class;
    }

    public function getDomainObject(): string
    {
        return CapacityAssignmentDomainObject::class;
    }

    public function findByEventId(int $eventId, QueryParamsDTO $params): LengthAwarePaginator
    {
        $where = [
            [CapacityAssignmentDomainObjectAbstract::EVENT_ID, '=', $eventId]
        ];

        if (!empty($params->query)) {
            $where[] = static function (Builder $builder) use ($params) {
                $builder
                    ->where(CapacityAssignmentDomainObjectAbstract::NAME, 'ilike', '%' . $params->query . '%');
            };
        }

        $this->model = $this->model->orderBy(
            $params->sort_by ?? CapacityAssignmentDomainObject::getDefaultSort(),
            $params->sort_direction ?? CapacityAssignmentDomainObject::getDefaultSortDirection(),
        );

        return $this->paginateWhere(
            where: $where,
            limit: $params->per_page,
            page: $params->page,
        );
    }
}
