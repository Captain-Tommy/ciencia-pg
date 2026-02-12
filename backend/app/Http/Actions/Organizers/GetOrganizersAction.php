<?php

namespace Ciencia\Http\Actions\Organizers;

use Ciencia\DomainObjects\ImageDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Interfaces\OrganizerRepositoryInterface;
use Ciencia\Resources\Organizer\OrganizerResource;
use Illuminate\Http\JsonResponse;

class GetOrganizersAction extends BaseAction
{
    public function __construct(private readonly OrganizerRepositoryInterface $organizerRepository)
    {
    }

    public function __invoke(): JsonResponse
    {
        $organizers = $this->organizerRepository
            ->loadRelation(ImageDomainObject::class)
            ->findwhere([
                'account_id' => $this->getAuthenticatedAccountId(),
            ]);

        return $this->resourceResponse(
            resource: OrganizerResource::class,
            data: $organizers,
        );
    }
}
