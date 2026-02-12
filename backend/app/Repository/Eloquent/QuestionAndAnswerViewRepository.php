<?php

namespace Ciencia\Repository\Eloquent;

use Ciencia\DomainObjects\QuestionAndAnswerViewDomainObject;
use Ciencia\Models\QuestionAndAnswerView;
use Ciencia\Repository\Interfaces\QuestionAndAnswerViewRepositoryInterface;

class QuestionAndAnswerViewRepository extends BaseRepository implements QuestionAndAnswerViewRepositoryInterface
{
    protected function getModel(): string
    {
        return QuestionAndAnswerView::class;
    }

    public function getDomainObject(): string
    {
        return QuestionAndAnswerViewDomainObject::class;
    }
}
