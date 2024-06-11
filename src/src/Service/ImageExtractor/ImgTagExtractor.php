<?php

declare(strict_types=1);

namespace App\Service\ImageExtractor;

use App\Service\ImageExtractorInterface;
use Symfony\Component\DomCrawler\Crawler;

class ImgTagExtractor implements ImageExtractorInterface
{
    public function extract(Crawler $crawler, string $baseUrl, callable $processImage): array
    {
        return $crawler->filter('img')->each(function (Crawler $node) use ($baseUrl, $processImage) {
            return $processImage($node->attr('src'), $baseUrl);
        });
    }
}