<?php
declare(strict_types=1);

namespace App\Service;


use App\Entity\Product;
use App\Storage\ProductStorageException;
use App\Storage\ProductStorageInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductService implements ProductServiceInterface
{

    private ProductStorageInterface $storage;

    public function __construct(ProductStorageInterface $productStorage)
    {
        $this->storage = $productStorage;
    }

    public function validate(Request $request): void
    {
        $price = $request->get('price');
        $name = $request->get('name');
        if (null === $price || '' === $price || !is_numeric($price) || (float) $price <= 0) {
            throw new ProductServiceValidationException('Invalid price, should be greater than 0');
        }
        if (null === $name || '' === $name) {
            throw new ProductServiceValidationException('Invalid name, should be not empty');
        }
    }

    public function create(Request $request): Product
    {
        $product = new Product();
        $product->setName($request->get('name'));
        $product->setPrice((float) $request->get('price'));

        try {
            return $this->storage->persist($product);
        } catch (ProductStorageException $e) {
            throw new ProductServiceException('Failed to persist product', 0, $e);
        }
    }
}
