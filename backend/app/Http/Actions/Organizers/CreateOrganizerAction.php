<?php

namespace Ciencia\Http\Actions\Organizers;

use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Organizer\UpsertOrganizerRequest;
use Ciencia\Http\ResponseCodes;
use Ciencia\Resources\Organizer\OrganizerResource;
use Ciencia\Services\Application\Handlers\Organizer\CreateOrganizerHandler;
use Ciencia\Services\Application\Handlers\Organizer\DTO\CreateOrganizerDTO;
use Illuminate\Http\JsonResponse;

class CreateOrganizerAction extends BaseAction
{
    public function __construct(private readonly CreateOrganizerHandler $createOrganizerHandler)
    {
    }

    public function __invoke(UpsertOrganizerRequest $request): JsonResponse
    {
        $organizerData = array_merge(
            $request->validated(),
            [
                'account_id' => $this->getAuthenticatedAccountId(),
            ]
        );

        $organizer = $this->createOrganizerHandler->handle(
            organizerData: CreateOrganizerDTO::fromArray($organizerData),
        );

        return $this->resourceResponse(
            resource: OrganizerResource::class,
            data: $organizer,
            statusCode: ResponseCodes::HTTP_CREATED,
        );
    }
}
