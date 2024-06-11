<?php

declare(strict_types=1);

namespace App\Service\ImageExtractor;

use App\Service\ImageExtractorInterface;
use Symfony\Component\DomCrawler\Crawler;

class BackgroundImageExtractor implements ImageExtractorInterface
{
    public function extract(Crawler $crawler, string $baseUrl, callable $processImage): array
    {
        return $crawler->filter('[style*="background-image"]')->each(function (Crawler $node) use ($baseUrl, $processImage) {
            $style = $node->attr('style');
            preg_match('/background-image\s*:\s*url\(([^)]+)\)/', $style, $matches);

            return $processImage($matches[1] ?? null, $baseUrl);
        });
    }
}