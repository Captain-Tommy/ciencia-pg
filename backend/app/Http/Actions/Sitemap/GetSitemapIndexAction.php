<?php

declare(strict_types=1);

namespace Ciencia\Http\Actions\Sitemap;

use Ciencia\Http\Actions\BaseAction;
use Ciencia\Services\Application\Handlers\Sitemap\GetSitemapIndexHandler;
use Illuminate\Http\Response;

class GetSitemapIndexAction extends BaseAction
{
    private const CONTENT_TYPE_XML = 'application/xml';

    public function __construct(
        private readonly GetSitemapIndexHandler $handler,
    )
    {
    }

    public function __invoke(): Response
    {
        $xml = $this->handler->handle();
        $cacheTtl = (int)config('sitemap.cache_ttl');

        return $this->xmlResponse(
            xmlContent: $xml,
            headers: [
                'Content-Type' => self::CONTENT_TYPE_XML,
                'Cache-Control' => "public, max-age=$cacheTtl",
            ]);
    }
}
