<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Storage\ProductStorageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductService implements ProductServiceInterface
{
    private ProductStorageInterface $storage;

    public function __construct(ProductStorageInterface $productStorage)
    {
        $this->storage = $productStorage;
    }

    public function create(Request $request): JsonResponse
    {
        $response = new JsonResponse();

        try {
            $this->validate($request);

            $product = new Product();
            $product->setName($request->get('name'));
            $product->setPrice((float) $request->get('price'));
            $product = $this->storage->persist($product);
            $response->setData($product->toArray());
            $response->setStatusCode(JsonResponse::HTTP_CREATED);
            $response->headers->set('Location', '/product/'.$product->getId());
        } catch (ProductServiceValidationException $e) {
            $response->setData([
                'title' => $e->getMessage(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'detail' => $e->getMessage(),
            ]);
            $response->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            //TODO log error an also send to sentry
            $response->setData([
                'title' => 'Internal Server Error',
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'detail' => 'Internal Server Error',
            ]);
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->setEncodingOptions(\JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES);

        return $response;
    }

    /**
     * @throws ProductServiceValidationException
     */
    private function validate(Request $request): void
    {
        $price = $request->get('price');
        $name = $request->get('name');
        if (null === $price || '' === $price || !\is_numeric($price) || (float) $price <= 0) {
            throw new ProductServiceValidationException('Invalid price, should be greater than 0');
        }
        if (null === $name || '' === $name) {
            throw new ProductServiceValidationException('Invalid name, should be not empty');
        }
    }
}
