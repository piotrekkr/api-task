<?php

declare(strict_types=1);

namespace App\Service\Product\Validation;

use Symfony\Component\HttpFoundation\Request;

interface CreateProductDataValidatorInterface
{
    /**
     * @throws CreateProductDataValidationException
     */
    public function validate(Request $request): void;
}
