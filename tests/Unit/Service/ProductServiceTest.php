<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\ProductService;
use App\Storage\ProductStorageInterface;
use App\Tests\Mocks\Entity\Product;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @group Unit
 */
class ProductServiceTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidRequestDataProvider
     */
    public function productServiceReturnBadRequestOnInvalidData(?string $name, ?string $price, string $errorMsg): void
    {
        $service = new ProductService($this->getStorageMock());
        $response = $service->create($this->getRequestMock($name, $price));
        self::assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertJson($response->getContent());
        $json = \json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('title', $json);
        self::assertArrayHasKey('status', $json);
        self::assertArrayHasKey('detail', $json);
        self::assertStringContainsString($errorMsg, $json['title']);
    }

    /**
     * @test
     */
    public function productServiceReturnInternalServerErrorOnStorageFailure(): void
    {
        $service = new ProductService($this->getStorageMock(true));
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
        $service = new ProductService($this->getStorageMock());
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

    public function invalidRequestDataProvider(): ?\Generator
    {
        yield [null, null, 'Invalid price'];
        yield ['', '', 'Invalid price'];
        yield [null, '', 'Invalid price'];
        yield ['', null, 'Invalid price'];
        yield ['test', '', 'Invalid price'];
        yield ['test', null, 'Invalid price'];
        yield [null, '1.23', 'Invalid name'];
        yield ['', '1.23', 'Invalid name'];
        yield ['test', 'not numeric', 'Invalid price'];
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

    private function getRequestMock(?string $name = null, ?string $price = null): Request
    {
        $data = [];
        if (null !== $name) {
            $data['name'] = $name;
        }
        if (null !== $price) {
            $data['price'] = $price;
        }

        return new Request([], $data);
    }

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
