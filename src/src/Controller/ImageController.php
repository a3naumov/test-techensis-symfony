<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ImageScraperService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImageController extends AbstractController
{
    #[Route(path: '/', name: 'app_homepage_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig');
    }

    #[Route(path: '/images', name: 'app_images_get', methods: ['GET'])]
    public function getImages(Request $request, ImageScraperService $imageScraperService): JsonResponse
    {
        $imagesData = $imageScraperService->scrapeImages($request->get('url'));

        return $this->json([
            'images' => array_column($imagesData, 'image'),
            'totalSize' => array_sum(array_column($imagesData, 'size')),
        ]);
    }
}
