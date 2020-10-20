<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @group Functional
 */
class CreateProductTest extends WebTestCase
{
    /**
     * @test
     */
    public function successWhenCorrectProductData(): void
    {
        $client = static::createClient();
        $client->request('POST', '/product', ['name' => 'test', 'price' => '10.2']);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('Content-Type', 'application/json');
        $json = \json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('id', $json);
        self::assertArrayHasKey('name', $json);
        self::assertArrayHasKey('price', $json);
        self::assertSame(1, $json['id']);
        self::assertSame('test', $json['name']);
        self::assertSame(10.2, $json['price']);
    }

    /**
     * @test
     * @dataProvider badProductDataProvider
     */
    public function errorWhenIncorrectProductData(?string $name, ?string $price, string $errorMsg): void
    {
        $client = static::createClient();
        $client->request('POST', '/product', ['name' => $name, 'price' => $price]);

        self::assertResponseStatusCodeSame(400);
        self::assertResponseHeaderSame('Content-Type', 'application/json');
        $json = \json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('title', $json);
        self::assertArrayHasKey('status', $json);
        self::assertArrayHasKey('detail', $json);
        self::assertStringContainsString($errorMsg, $json['title']);
    }

    public function badProductDataProvider(): ?\Generator
    {
        yield [null, 'price' => '123', 'Invalid name'];
        yield ['', 'price' => '123', 'Invalid name'];
        yield ['correct_name', null, 'Invalid price'];
        yield ['correct_name', '', 'Invalid price'];
    }
}
