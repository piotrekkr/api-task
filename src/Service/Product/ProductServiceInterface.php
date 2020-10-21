<?php

declare(strict_types=1);

namespace App\Service\Product;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface ProductServiceInterface
{
    public function create(Request $request): JsonResponse;
}
