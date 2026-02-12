<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Sitemap;

use Ciencia\Exceptions\ResourceNotFoundException;
use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\Sitemap\GetSitemapEventsHandler;
use Illuminate\Http\Response;

class GetSitemapEventsAction extends BaseAction
{
    private const CONTENT_TYPE_XML = 'application/xml';

    public function __construct(
        private readonly GetSitemapEventsHandler $handler,
    )
    {
    }

    public function __invoke(int $page): Response
    {
        try {
            $xml = $this->handler->handle($page);
            $cacheTtl = (int) config('sitemap.cache_ttl');

            return $this->xmlResponse(
                xmlContent: $xml,
                headers: [
                    'Content-Type' => self::CONTENT_TYPE_XML,
                    'Cache-Control' => "public, max-age=$cacheTtl",
                ]
            );
        } catch (ResourceNotFoundException) {
            return $this->notFoundResponse();
        }
    }
}
