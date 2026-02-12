<?php

namespace Ciencia\Http\Actions\Organizers;

use Ciencia\DomainObjects\OrganizerDomainObject;
use Ciencia\Exceptions\AccountNotVerifiedException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Organizer\UpdateOrganizerStatusRequest;
use Ciencia\Http\ResponseCodes;
use Ciencia\Resources\Organizer\OrganizerResource;
use Ciencia\Services\Application\Handlers\Organizer\DTO\UpdateOrganizerStatusDTO;
use Ciencia\Services\Application\Handlers\Organizer\UpdateOrganizerStatusHandler;
use Illuminate\Http\JsonResponse;

class UpdateOrganizerStatusAction extends BaseAction
{
    public function __construct(
        private readonly UpdateOrganizerStatusHandler $updateOrganizerStatusHandler,
    )
    {
    }

    public function __invoke(UpdateOrganizerStatusRequest $request, int $organizerId): JsonResponse
    {
        $this->isActionAuthorized($organizerId, OrganizerDomainObject::class);

        try {
            $updatedOrganizer = $this->updateOrganizerStatusHandler->handle(UpdateOrganizerStatusDTO::fromArray([
                'status' => $request->input('status'),
                'organizerId' => $organizerId,
                'accountId' => $this->getAuthenticatedAccountId(),
            ]));
        } catch (AccountNotVerifiedException $e) {
            return $this->errorResponse($e->getMessage(), ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->resourceResponse(OrganizerResource::class, $updatedOrganizer);
    }
}
