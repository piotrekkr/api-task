<?php

declare(strict_types=1);

namespace App\Service\Product;

use App\Dto\ProductDto;
use Symfony\Component\HttpFoundation\JsonResponse;

interface ProductServiceInterface
{
    public function create(ProductDto $productDto): JsonResponse;
}
