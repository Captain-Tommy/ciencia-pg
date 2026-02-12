<?php

namespace Ciencia\Services\Application\Handlers\EmailTemplate\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;
use Ciencia\DomainObjects\Enums\EmailTemplateType;

class GetEmailTemplatesDTO extends BaseDataObject
{
    public function __construct(
        public readonly int                $account_id,
        public readonly ?int               $organizer_id = null,
        public readonly ?int               $event_id = null,
        public readonly ?EmailTemplateType $template_type = null,
        public readonly bool               $include_inactive = false,
    )
    {
    }
}
