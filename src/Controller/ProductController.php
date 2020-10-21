<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ProductDto;
use App\Service\Product\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product", methods={"POST"})
     */
    public function upload(
        Request $request,
        ProductService $productService
    ): JsonResponse {
        return $productService->create(ProductDto::createFromRequest($request));
    }
}
