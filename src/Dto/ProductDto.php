<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;

class ProductDto
{
    private ?string $name;

    private ?string $price;

    public function __construct(?string $name, ?string $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public static function createFromRequest(Request $request): ProductDto
    {
        return new static($request->get('name'), $request->get('price'));
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'price' => $this->getPrice(),
        ];
    }
}
