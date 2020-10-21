<?php

declare(strict_types=1);

namespace App\Service\Product\Validation;

use App\Dto\ProductDto;

class CreateProductDataValidator implements CreateProductDataValidatorInterface
{
    /**
     * @throws CreateProductDataValidationException
     */
    public function validate(ProductDto $productDto): void
    {
        $price = $productDto->getPrice();
        $name = $productDto->getName();
        if (null === $price || '' === $price || !\is_numeric($price) || (float) $price <= 0) {
            throw new CreateProductDataValidationException('Invalid price, should be greater than 0');
        }
        if (null === $name || '' === $name) {
            throw new CreateProductDataValidationException('Invalid name, should be not empty');
        }
    }
}
