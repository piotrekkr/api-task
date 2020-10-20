<?php

declare(strict_types=1);

namespace App\Storage;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductStorage implements ProductStorageInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persist(Product $product): Product
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }
}
