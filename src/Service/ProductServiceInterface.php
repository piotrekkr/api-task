<?php
declare(strict_types=1);

namespace App\Service;


use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;

interface ProductServiceInterface
{
    /**
     * @throws ProductServiceValidationException
     */
    public function validate(Request $request): void;

    /**
     * @throws ProductServiceException
     */
    public function create(Request $request): Product;
}