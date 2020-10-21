<?php

declare(strict_types=1);

namespace App\Service\Product\Validation;

use App\Dto\ProductDto;

interface CreateProductDataValidatorInterface
{
    /**
     * @throws CreateProductDataValidationException
     */
    public function validate(ProductDto $productDto): void;
}
