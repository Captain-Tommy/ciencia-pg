<?php

namespace Ciencia\Repository\Eloquent;


use Ciencia\DomainObjects\QuestionAnswerDomainObject;
use Ciencia\Models\QuestionAnswer;
use Ciencia\Repository\Interfaces\QuestionAnswerRepositoryInterface;

class QuestionAnswerRepository extends BaseRepository implements QuestionAnswerRepositoryInterface
{
    protected function getModel(): string
    {
        return QuestionAnswer::class;
    }

    public function getDomainObject(): string
    {
        return QuestionAnswerDomainObject::class;
    }
}
