<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use App\Tests\Mocks\Entity\Product;

trait GetProductMockTrait
{
    private function getProductMock(?int $id = null, string $name = null, ?float $price = null): Product
    {
        $product = new Product($id);
        if (null !== $name) {
            $product->setName($name);
        }
        if (null !== $price) {
            $product->setPrice($price);
        }

        return $product;
    }
}
