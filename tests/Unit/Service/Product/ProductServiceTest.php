<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Product;

use App\Service\Product\ProductService;
use App\Service\Product\Validation\CreateProductDataValidator;
use App\Storage\ProductStorageInterface;
use App\Tests\Traits\GetProductMockTrait;
use App\Tests\Traits\GetRequestMockTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @group Unit
 */
class ProductServiceTest extends TestCase
{
    use GetRequestMockTrait;

    use GetProductMockTrait;

    /**
     * @test
     */
    public function productServiceReturnBadRequestOnInvalidData(): void
    {
        $service = new ProductService($this->getStorageMock(), new CreateProductDataValidator());
        $response = $service->create($this->getRequestMock());
        self::assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertJson($response->getContent());
        $json = \json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('title', $json);
        self::assertArrayHasKey('status', $json);
        self::assertArrayHasKey('detail', $json);
        self::assertStringContainsString('Invalid price', $json['title']);
    }

    /**
     * @test
     */
    public function productServiceReturnInternalServerErrorOnStorageFailure(): void
    {
        $service = new ProductService($this->getStorageMock(true), new CreateProductDataValidator());
        $response = $service->create($this->getRequestMock('test', '1.23'));
        self::assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        self::assertJson($response->getContent());
        $json = \json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('title', $json);
        self::assertArrayHasKey('status', $json);
        self::assertArrayHasKey('detail', $json);
        self::assertStringContainsString('Internal Server Error', $json['title']);
    }

    /**
     * @test
     */
    public function productServiceReturnCreatedResponseOnValidData(): void
    {
        $service = new ProductService($this->getStorageMock(), new CreateProductDataValidator());
        $response = $service->create($this->getRequestMock('test', '1.23'));
        self::assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertSame('application/json', $response->headers->get('Content-Type'));
        self::assertSame('/product/1', $response->headers->get('Location'));
        $json = \json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('id', $json);
        self::assertArrayHasKey('name', $json);
        self::assertArrayHasKey('price', $json);
        self::assertSame(1, $json['id']);
        self::assertSame('test', $json['name']);
        self::assertSame(1.23, $json['price']);
    }

    private function getStorageMock(bool $throwException = false): ProductStorageInterface
    {
        $storage = $this
            ->getMockBuilder(ProductStorageInterface::class)
            ->setMethods(['persist'])
            ->getMock()
        ;
        if ($throwException) {
            $storage
                ->method('persist')
                ->willThrowException(new \RuntimeException('storage error'))
            ;
        } else {
            $storage
                ->method('persist')
                ->willReturn($this->getProductMock(1, 'test', 1.23))
            ;
        }

        return $storage;
    }
}
