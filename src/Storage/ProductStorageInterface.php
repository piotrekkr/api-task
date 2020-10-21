<?php

declare(strict_types=1);

namespace App\Storage;

use App\Entity\Product;

interface ProductStorageInterface
{
    /**
     * @throws ProductStorageException
     */
    public function persist(Product $product): string;
}
