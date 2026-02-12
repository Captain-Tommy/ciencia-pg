<?php

namespace Ciencia\Http\Actions\TicketLookup;

use Ciencia\Http\Actions\BaseAction;
use Ciencia\Http\Request\TicketLookup\SendTicketLookupEmailRequest;
use Ciencia\Services\Application\Handlers\TicketLookup\DTO\SendTicketLookupEmailDTO;
use Ciencia\Services\Application\Handlers\TicketLookup\SendTicketLookupEmailHandler;
use Illuminate\Http\JsonResponse;

class SendTicketLookupEmailAction extends BaseAction
{
    public function __construct(
        private readonly SendTicketLookupEmailHandler $sendTicketLookupEmailHandler,
    ) {
    }

    public function __invoke(SendTicketLookupEmailRequest $request): JsonResponse
    {
        $this->sendTicketLookupEmailHandler->handle(
            new SendTicketLookupEmailDTO(
                email: $request->validated('email'),
            )
        );

        return $this->jsonResponse(
            data: [
                'message' => __('If you have tickets associated with this email, we will send you an email with the details.'),
            ]
        );
    }
}
