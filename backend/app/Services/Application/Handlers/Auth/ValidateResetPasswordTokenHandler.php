<?php

namespace Ciencia\Services\Application\Handlers\Auth;

use Ciencia\DomainObjects\PasswordResetTokenDomainObject;
use Ciencia\Exceptions\InvalidPasswordResetTokenException;
use Ciencia\Services\Domain\Auth\ResetPasswordTokenValidateService;

class ValidateResetPasswordTokenHandler
{
    private ResetPasswordTokenValidateService $passwordTokenValidateService;

    public function __construct(ResetPasswordTokenValidateService $passwordTokenValidateService)
    {
        $this->passwordTokenValidateService = $passwordTokenValidateService;
    }

    /**
     * @throws InvalidPasswordResetTokenException
     */
    public function handle(string $token): PasswordResetTokenDomainObject
    {
        return $this->passwordTokenValidateService->validateAndFetchToken($token);
    }
}
