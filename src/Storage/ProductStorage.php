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

    public function persist(Product $product): string
    {
        try {
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return (string) $product->getId();
        } catch (\Throwable $e) {
            throw new ProductStorageException('Error while storing product: '.$e->getMessage(), 0, $e);
        }
    }
}
