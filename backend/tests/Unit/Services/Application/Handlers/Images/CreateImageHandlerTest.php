<?php

namespace Tests\Unit\Services\Application\Handlers\Images;

use Ciencia\DomainObjects\Enums\ImageType;
use Ciencia\DomainObjects\ImageDomainObject;
use Ciencia\DomainObjects\UserDomainObject;
use Ciencia\Repository\Interfaces\EventRepositoryInterface;
use Ciencia\Repository\Interfaces\ImageRepositoryInterface;
use Ciencia\Repository\Interfaces\OrganizerRepositoryInterface;
use Ciencia\Services\Application\Handlers\Images\CreateImageHandler;
use Ciencia\Services\Application\Handlers\Images\DTO\CreateImageDTO;
use Ciencia\Services\Domain\Image\ImageUploadService;
use Illuminate\Http\UploadedFile;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class CreateImageHandlerTest extends TestCase
{
    private ImageUploadService $imageUploadService;
    private CreateImageHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->imageUploadService = m::mock(ImageUploadService::class);
        $organizerRepository = m::mock(OrganizerRepositoryInterface::class);
        $eventRepository = m::mock(EventRepositoryInterface::class);
        $imageRepository = m::mock(ImageRepositoryInterface::class);

        $this->handler = new CreateImageHandler(
            $this->imageUploadService,
            $organizerRepository,
            $eventRepository,
            $imageRepository
        );
    }

    public function testHandleSuccessfullyCreatesImage(): void
    {
        $uploadedFile = m::mock(UploadedFile::class);
        $imageDomainObject = m::mock(ImageDomainObject::class);
        $accountId = 123;

        $dto = new CreateImageDTO(
            userId: 42,
            accountId: $accountId,
            image: $uploadedFile
        );

        $this->imageUploadService
            ->shouldReceive('upload')
            ->once()
            ->withArgs([
                $uploadedFile,
                42,
                UserDomainObject::class,
                ImageType::GENERIC->name,
                $accountId,
            ])
            ->andReturn($imageDomainObject);

        $result = $this->handler->handle($dto);

        $this->assertSame($imageDomainObject, $result);
    }

    protected function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }
}
