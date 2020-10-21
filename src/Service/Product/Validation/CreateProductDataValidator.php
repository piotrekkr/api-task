<?php

declare(strict_types=1);

namespace App\Service\Product\Validation;

use Symfony\Component\HttpFoundation\Request;

class CreateProductDataValidator implements CreateProductDataValidatorInterface
{
    /**
     * @throws CreateProductDataValidationException
     */
    public function validate(Request $request): void
    {
        $price = $request->get('price');
        $name = $request->get('name');
        if (null === $price || '' === $price || !\is_numeric($price) || (float) $price <= 0) {
            throw new CreateProductDataValidationException('Invalid price, should be greater than 0');
        }
        if (null === $name || '' === $name) {
            throw new CreateProductDataValidationException('Invalid name, should be not empty');
        }
    }
}
