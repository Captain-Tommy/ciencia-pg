<?php

namespace Ciencia\Http\Actions\Questions;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Questions\UpsertQuestionRequest;
use Ciencia\Http\ResponseCodes;
use Ciencia\Resources\Question\QuestionResource;
use Ciencia\Services\Application\Handlers\Question\CreateQuestionHandler;
use Ciencia\Services\Application\Handlers\Question\DTO\UpsertQuestionDTO;
use Illuminate\Http\JsonResponse;

class CreateQuestionAction extends BaseAction
{
    private CreateQuestionHandler $createQuestionHandler;

    public function __construct(CreateQuestionHandler $createQuestionHandler)
    {
        $this->createQuestionHandler = $createQuestionHandler;
    }

    public function __invoke(UpsertQuestionRequest $request, int $eventId): JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        $question = $this->createQuestionHandler->handle(UpsertQuestionDTO::fromArray([
            'title' => $request->input('title'),
            'type' => $request->input('type'),
            'required' => $request->boolean('required'),
            'options' => $request->input('options'),
            'event_id' => $eventId,
            'product_ids' => $request->input('product_ids'),
            'belongs_to' => $request->input('belongs_to'),
            'is_hidden' => $request->boolean('is_hidden'),
            'description' => $request->input('description'),
        ]));

        return $this->resourceResponse(
            resource: QuestionResource::class,
            data: $question,
            statusCode: ResponseCodes::HTTP_CREATED
        );
    }
}
