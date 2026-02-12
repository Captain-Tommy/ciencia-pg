<?php

namespace Ciencia\Http\Actions\CheckInLists\Public;

use Ciencia\Http\Actions\BaseAction;
use Ciencia\Resources\CheckInList\CheckInListResourcePublic;
use Ciencia\Services\Application\Handlers\CheckInList\Public\GetCheckInListPublicHandler;
use Illuminate\Http\JsonResponse;

class GetCheckInListPublicAction extends BaseAction
{
    public function __construct(
        private readonly GetCheckInListPublicHandler $getCheckInListPublicHandler,
    )
    {
    }

    public function __invoke(string $checkInListShortId): JsonResponse
    {
        $checkInList = $this->getCheckInListPublicHandler->handle($checkInListShortId);

        return $this->resourceResponse(
            resource: CheckInListResourcePublic::class,
            data: $checkInList,
        );
    }
}
