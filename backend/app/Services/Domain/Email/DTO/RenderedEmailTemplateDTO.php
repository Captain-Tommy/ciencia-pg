<?php

namespace Ciencia\Services\Domain\Email\DTO;

use Ciencia\DataTransferObjects\BaseDataObject;

class RenderedEmailTemplateDTO extends BaseDataObject
{
    public function __construct(
        public readonly string $subject,
        public readonly string $body,
        public readonly ?array $cta = null,
    )
    {
    }
}