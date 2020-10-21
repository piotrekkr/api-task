<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Product\Validation;

use App\Dto\ProductDto;
use App\Service\Product\Validation\CreateProductDataValidationException;
use App\Service\Product\Validation\CreateProductDataValidator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @group Unit
 */
class CreateProductDataValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidDataProvider
     */
    public function shouldThrowExceptionOnInvalidData(?string $name, ?string $price): void
    {
        $this->expectException(CreateProductDataValidationException::class);
        $validator = new CreateProductDataValidator();
        $validator->validate(new ProductDto($name, $price));
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function shouldNotThrowExceptionOnValidData(): void
    {
        $validator = new CreateProductDataValidator();
        $validator->validate(new ProductDto('test', '1.23'));
    }

    public function invalidDataProvider(): ?\Generator
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
}
