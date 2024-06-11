<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;

interface ImageExtractorInterface
{
    public function extract(Crawler $crawler, string $baseUrl, callable $processImage): array;
}