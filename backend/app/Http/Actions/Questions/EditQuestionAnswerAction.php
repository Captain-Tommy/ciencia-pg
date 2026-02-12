<?php

namespace Ciencia\Http\Actions\Questions;

use Ciencia\DomainObjects\EventDomainObject;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\Questions\EditQuestionAnswerRequest;
use Ciencia\Services\Application\Handlers\Question\DTO\EditQuestionAnswerDTO;
use Ciencia\Services\Application\Handlers\Question\EditQuestionAnswerHandler;
use Ciencia\Services\Domain\Question\Exception\InvalidAnswerException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class EditQuestionAnswerAction extends BaseAction
{
    public function __construct(
        private readonly EditQuestionAnswerHandler $editQuestionAnswerHandler,
    )
    {
    }

    public function __invoke(
        int                       $eventId,
        int                       $questionId,
        int                       $questionAnswerId,
        EditQuestionAnswerRequest $request,
    ): Response
    {
        $this->isActionAuthorized($eventId, EventDomainObject::class);

        try {
            $this->editQuestionAnswerHandler->handle(new EditQuestionAnswerDTO(
                questionAnswerId: $questionAnswerId,
                eventId: $eventId,
                answer: $request->validated('answer'),
            ));
        } catch (InvalidAnswerException $e) {
            throw ValidationException::withMessages(['answer.answer' => $e->getMessage()]);
        }

        return $this->noContentResponse();
    }
}
