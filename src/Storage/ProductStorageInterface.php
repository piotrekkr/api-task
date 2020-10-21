<?php

declare(strict_types=1);

namespace App\Storage;

use App\Entity\Product;

interface ProductStorageInterface
{
    /**
     * @throws ProductStorageException
     *
     * @return Product persisted product instance
     */
    public function persist(Product $product): Product;
}
