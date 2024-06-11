<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImageScraperService
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly array $extractors,
    ) {
    }

    public function scrapeImages(string $url): array
    {
        $url = $this->sanitizeUrl($url);
        $htmlContent = $this->fetchContent($url);
        $crawler = new Crawler($htmlContent);

        $images = [];

        foreach ($this->extractors as $extractor) {
            $images = array_merge($images, $extractor->extract(
                $crawler,
                $url,
                fn(?string $image, string $baseUrl) => $this->processImage($image, $baseUrl),
            ));
        }

        return array_filter($images);
    }

    private function sanitizeUrl(string $url): string
    {
        $url = urldecode(trim($url));

        return str_ends_with($url, '/') ? substr($url, 0, -1) : $url;
    }

    private function fetchContent(string $url): string
    {
        $response = $this->httpClient->request(Request::METHOD_GET, $url);

        return $response->getContent();
    }

    private function processImage(?string $image, string $baseUrl): ?array
    {
        if ($image) {
            $image = parse_url($image, PHP_URL_SCHEME) ? $image : $baseUrl . $image;
            $size = $this->getImageSize($image);

            if ($size) {
                return [
                    'image' => $image,
                    'size' => $size,
                ];
            }
        }

        return null;
    }

    private function getImageSize(string $url): ?int
    {
        $response = $this->httpClient->request(Request::METHOD_HEAD, $url);
        $headers = $response->getHeaders();

        return isset($headers['content-length']) && is_array($headers['content-length'])
            ? (int) reset($headers['content-length'])
            : null;
    }
}
