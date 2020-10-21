<?php

declare(strict_types=1);

namespace App\Tests\Unit\Storage;

use App\Entity\Product;
use App\Storage\ProductStorage;
use App\Storage\ProductStorageException;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @group Unit
 */
class ProductStorageTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnProductIdentifier(): void
    {
        $storage = new ProductStorage($this->getEntityManagerMock());
        $productIdentifier = $storage->persist(new Product());
        self::assertSame('1', $productIdentifier);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionOnManagerFailure(): void
    {
        $this->expectException(ProductStorageException::class);
        $storage = new ProductStorage($this->getEntityManagerMock(true));
        $storage->persist(new Product());
    }

    private function getEntityManagerMock(bool $throw = false): EntityManagerInterface
    {
        $mock = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->getMock()
        ;
        if ($throw) {
            $mock
                ->method('persist')
                ->willThrowException(new \RuntimeException('Persist failed'))
            ;
        } else {
            $mock
                ->method('persist')
                ->willReturnCallback(
                    static function (Product $product) {
                        $reflection = new \ReflectionClass($product);
                        $property = $reflection->getProperty('id');
                        $property->setAccessible(true);
                        $property->setValue($product, 1);
                        $property->setAccessible(false);
                    }
                )
            ;
        }

        return $mock;
    }
}
