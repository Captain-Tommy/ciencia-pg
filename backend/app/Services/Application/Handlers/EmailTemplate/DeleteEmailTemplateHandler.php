<?php

namespace Ciencia\Services\Application\Handlers\EmailTemplate;

use Ciencia\Exceptions\EmailTemplateNotFoundException;
use Ciencia\Repository\Interfaces\EmailTemplateRepositoryInterface;
use Ciencia\Services\Application\Handlers\EmailTemplate\DTO\DeleteEmailTemplateDTO;

class DeleteEmailTemplateHandler
{
    public function __construct(
        private readonly EmailTemplateRepositoryInterface $emailTemplateRepository
    )
    {
    }

    /**
     * @throws EmailTemplateNotFoundException
     */
    public function handle(DeleteEmailTemplateDTO $dto): bool
    {
        $template = $this->emailTemplateRepository->findFirstWhere([
            'id' => $dto->id,
            'account_id' => $dto->account_id,
        ]);

        if (!$template) {
            throw new EmailTemplateNotFoundException(__('Email template not found'));
        }

        return $this->emailTemplateRepository->deleteById($template->getId());
    }
}
