<?php

namespace Ciencia\Services\Application\Handlers\Question;

use Ciencia\Services\Application\Handlers\Question\DTO\EditQuestionAnswerDTO;
use Ciencia\Services\Domain\Question\EditQuestionAnswerService;
use Ciencia\Services\Domain\Question\Exception\InvalidAnswerException;
use JsonException;

class EditQuestionAnswerHandler
{
    public function __construct(
        private readonly EditQuestionAnswerService $editQuestionAnswerService,
    )
    {
    }

    /**
     * @throws InvalidAnswerException
     * @throws JsonException
     */
    public function handle(EditQuestionAnswerDTO $editQuestionAnswerDTO): void
    {
        $this->editQuestionAnswerService->editQuestionAnswer(
            eventId: $editQuestionAnswerDTO->eventId,
            questionAnswerId: $editQuestionAnswerDTO->questionAnswerId,
            answer: $editQuestionAnswerDTO->answer,
        );
    }
}
