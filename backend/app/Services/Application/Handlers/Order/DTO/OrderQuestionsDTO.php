<?php

namespace Ciencia\Services\Application\Handlers\Order\DTO;

use Ciencia\DataTransferObjects\BaseDTO;

class OrderQuestionsDTO extends BaseDTO
{
    public function __construct(
        public readonly string|int $question_id,
        public readonly array      $response,
    )
    {
    }
}
