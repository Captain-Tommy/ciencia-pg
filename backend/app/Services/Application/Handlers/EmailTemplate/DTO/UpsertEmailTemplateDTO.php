<?php

namespace Ciencia\Services\Application\Handlers\EmailTemplate\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;
use Ciencia\DomainObjects\Enums\EmailTemplateEngine;
use Ciencia\DomainObjects\Enums\EmailTemplateType;

class UpsertEmailTemplateDTO extends BaseDataObject
{
    public function __construct(
        public readonly int                 $account_id,
        public readonly EmailTemplateType   $template_type,
        public readonly string              $subject,
        public readonly string              $body,
        public readonly ?int                $organizer_id = null,
        public readonly ?int                $event_id = null,
        public readonly ?int                $id = null,
        public readonly ?array              $cta = null,
        public readonly EmailTemplateEngine $engine = EmailTemplateEngine::LIQUID,
        public readonly bool                $is_active = true,
    )
    {
    }
}
