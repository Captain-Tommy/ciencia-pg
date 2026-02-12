<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Affiliates;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\Status\AffiliateStatus;
use Ciencia\Exceptions\ResourceConflictException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Affiliate\CreateUpdateAffiliateRequest;
use Ciencia\Http\ResponseCodes;
use Ciencia\Resources\Affiliate\AffiliateResource;
use Ciencia\Services\Application\Handlers\Affiliate\CreateAffiliateHandler;
use Ciencia\Services\Application\Handlers\Affiliate\DTO\UpsertAffiliateDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateAffiliateAction extends BaseAction
{
    public function __construct(
        private readonly CreateAffiliateHandler $createAffiliateHandler
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(CreateUpdateAffiliateRequest $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        try {
            $affiliate = $this->createAffiliateHandler->handle(
                $eventId,
                $this->getAuthenticatedAccountId(),
                new UpsertAffiliateDTO(
                    name: $request->input('name'),
                    code: $request->input('code'),
                    email: $request->input('email'),
                    status: AffiliateStatus::from($request->input('status', 'ACTIVE')),
                )
            );
        } catch (ResourceConflictException $e) {
            throw ValidationException::withMessages([
                'code' => $e->getMessage(),
            ]);
        }

        return $this->resourceResponse(
            resource: AffiliateResource::class,
            data: $affiliate,
            statusCode: ResponseCodes::HTTP_CREATED
        );
    }
}
