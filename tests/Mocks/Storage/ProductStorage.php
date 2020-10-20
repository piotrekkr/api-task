<?php

declare(strict_types=1);

namespace App\Tests\Mocks\Storage;

use App\Entity\Product;
use App\Storage\ProductStorageInterface;

class ProductStorage implements ProductStorageInterface
{
    public function persist(Product $product): Product
    {
        $productWithId = new class() extends Product {
            public function __construct()
            {
                $this->id = 1;
            }
        };
        $productWithId->setPrice($product->getPrice());
        $productWithId->setName($product->getName());

        return $productWithId;
    }
}
