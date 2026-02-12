<?php

declare(strict_types=1);

namespace Ciencia\Http\Request\Affiliate;

use Ciencia\Http\Request\BaseRequest;
use Ciencia\Validators\Rules\AffiliateRules;

class CreateUpdateAffiliateRequest extends BaseRequest
{
    public function rules(): array
    {
        return AffiliateRules::createRules();
    }
}