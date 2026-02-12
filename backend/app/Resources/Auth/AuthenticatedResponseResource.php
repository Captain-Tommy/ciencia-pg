<?php

namespace Ciencia\Resources\Auth;

use Ciencia\Resources\Account\AccountResource;
use Ciencia\Resources\User\UserResource;
use Ciencia\Services\Application\Handlers\Auth\DTO\AuthenticatedResponseDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AuthenticatedResponseDTO
 */
class AuthenticatedResponseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'token' => $this->token,
            'token_type' => 'bearer',
            'expires_in' => $this->expiresIn,
            'user' => new UserResource($this->user),
            'accounts' => AccountResource::collection($this->accounts),
        ];
    }
}
