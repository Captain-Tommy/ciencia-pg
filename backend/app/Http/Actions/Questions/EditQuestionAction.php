<?php

namespace Ciencia\Http\Actions\Questions;

use Ciencia\DomainObjects\Enums\QuestionBelongsTo;
use Ciencia\DomainObjects\Enums\QuestionTypeEnum;
use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Questions\UpsertQuestionRequest;
use Ciencia\Resources\Question\QuestionResource;
use Ciencia\Services\Application\Handlers\Question\DTO\UpsertQuestionDTO;
use Ciencia\Services\Application\Handlers\Question\EditQuestionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;

class EditQuestionAction extends BaseAction
{
    private EditQuestionHandler $editQuestionHandler;

    public function __construct(EditQuestionHandler $editQuestionHandler)
    {
        $this->editQuestionHandler = $editQuestionHandler;
    }

    /**
     * @throws Throwable
     */
    public function __invoke(UpsertQuestionRequest $request, int $eventId, int $questionId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $question = $this->editQuestionHandler->handle(
            questionId: $questionId,
            createQuestionDTO: UpsertQuestionDTO::fromArray([
                'title' => $request->input('title'),
                'type' => QuestionTypeEnum::fromName($request->input('type')),
                'required' => $request->boolean('required'),
                'options' => $request->input('options'),
                'event_id' => $eventId,
                'product_ids' => $request->input('product_ids'),
                'is_hidden' => $request->boolean('is_hidden'),
                'belongs_to' => QuestionBelongsTo::fromName($request->input('belongs_to')),
                'description' => $request->input('description'),
            ]));

        return $this->resourceResponse(QuestionResource::class, $question);
    }
}
