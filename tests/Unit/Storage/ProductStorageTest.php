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
            ->getMock()
        ;
        if ($throw) {
            $mock->method('persist')->willThrowException(new \RuntimeException('Persist failed'));
        }

        return $mock;
    }
}
