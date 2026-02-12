<?php

namespace Ciencia\Http\Actions\Users;

use Ciencia\Http\Actions\Auth\BaseAuthAction;
use Ciencia\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;

class GetMeAction extends BaseAuthAction
{
    public function __invoke(): JsonResponse
    {
        return $this->resourceResponse(
            resource: UserResource::class,
            data: $this->getAuthenticatedUser(),
        );
    }
}
