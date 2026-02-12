<?php

namespace Ciencia\Http\Actions\Questions;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\DomainObjects\ProductDomainObject;
use Ciencia\DomainObjects\ProductPriceDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Repository\Eloquent\Value\Relationship;
use Ciencia\Repository\Interfaces\QuestionRepositoryInterface;
use Ciencia\Resources\Question\QuestionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetQuestionsAction extends BaseAction
{
    private QuestionRepositoryInterface $questionRepository;

    public function __construct(QuestionRepositoryInterface $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function __invoke(Request $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $questions = $this->questionRepository
            ->loadRelation(
                new Relationship(ProductDomainObject::class, [
                    new Relationship(ProductPriceDomainObject::class)
                ])
            )
            ->findByEventId($eventId);

        return $this->resourceResponse(QuestionResource::class, $questions);
    }
}
