<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Tests\Mocks\Entity\Product;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @group Unit
 */
class ProductTest extends TestCase
{
    /**
     * @test
     */
    public function toArrayReturnsArrayWithProperKeysAndValues()
    {
        $product = new Product();
        $product->setPrice(1.23);
        $product->setName('test');
        self::assertSame(['id' => null, 'name' => 'test', 'price' => 1.23], $product->toArray());

        $productWithId = new Product(1);
        $productWithId->setPrice(1.23);
        $productWithId->setName('test');
        self::assertSame(['id' => 1, 'name' => 'test', 'price' => 1.23], $productWithId->toArray());
    }
}
