<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\ProductDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @group Unit
 */
class ProductDtoTest extends TestCase
{
    /**
     * @test
     * @dataProvider createFromRequestDataProvider
     */
    public function createFromRequest(?string $name, ?string $price): void
    {
        $request = $this->createRequest($name, $price);
        $productDto = ProductDto::createFromRequest($request);
        self::assertSame($request->get('name'), $productDto->getName());
        self::assertSame($request->get('price'), $productDto->getPrice());
    }

    /**
     * @test
     */
    public function toArray(): void
    {
        $productDto = new ProductDto('test', '1.23');
        self::assertSame(['name' => 'test', 'price' => '1.23'], $productDto->toArray());
    }

    public function createFromRequestDataProvider(): ?\Generator
    {
        yield [null, null];
        yield [null, ''];
        yield ['', null];
        yield ['name', '1.23'];
    }

    private function createRequest(?string $name = null, ?string $price = null): Request
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
}
