<?php

namespace Ciencia\Http\Actions\Questions;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Questions\SortQuestionsRequest;
use Ciencia\Services\Application\Handlers\Question\SortQuestionsHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class SortQuestionsAction extends BaseAction
{
    public function __construct(
        private readonly SortQuestionsHandler $sortQuestionsHandler
    )
    {
    }

    public function __invoke(SortQuestionsRequest $request, int $eventId): Response|JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        try {
            $this->sortQuestionsHandler->handle(
                $eventId,
                $request->validated(),
            );
        } catch (ResourceNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->noContentResponse();
    }
}
