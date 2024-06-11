<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\GetImagesDto;
use App\Service\ImageScraperService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ImageController extends AbstractController
{
    #[Route(path: '/', name: 'app_homepage_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig');
    }

    #[Route(path: '/images', name: 'app_images_get', methods: ['GET'])]
    public function getImages(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ImageScraperService $imageScraperService,
    ): JsonResponse {
        $query = $serializer->deserialize(json_encode($request->query->all()), GetImagesDto::class, JsonEncoder::FORMAT);
        $errors = $validator->validate($query);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $imagesData = $imageScraperService->scrapeImages($query->url);

        return $this->json([
            'images' => array_column($imagesData, 'image'),
            'totalSize' => array_sum(array_column($imagesData, 'size')),
        ]);
    }
}
