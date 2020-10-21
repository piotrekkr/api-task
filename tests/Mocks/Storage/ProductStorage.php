<?php

declare(strict_types=1);

namespace App\Tests\Mocks\Storage;

use App\Entity\Product;
use App\Storage\ProductStorageInterface;

class ProductStorage implements ProductStorageInterface
{
    public function persist(Product $product): string
    {
        return '1';
    }
}
