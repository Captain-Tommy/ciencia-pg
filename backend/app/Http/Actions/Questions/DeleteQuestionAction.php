<?php

namespace Ciencia\Http\Actions\Questions;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Exceptions\CannotDeleteEntityException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\Question\DeleteQuestionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class DeleteQuestionAction extends BaseAction
{
    private DeleteQuestionHandler $deleteQuestionHandler;

    public function __construct(DeleteQuestionHandler $deleteQuestionHandler)
    {
        $this->deleteQuestionHandler = $deleteQuestionHandler;
    }

    /**
     * @throws Throwable
     * @throws CannotDeleteEntityException
     */
    public function __invoke(int $eventId, int $questionId): Response|JsonResponse
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        try {
            $this->deleteQuestionHandler->handle($questionId, $eventId);
        } catch (CannotDeleteEntityException $exception) {
            return $this->errorResponse($exception->getMessage());
        }

        return $this->deletedResponse();
    }
}
