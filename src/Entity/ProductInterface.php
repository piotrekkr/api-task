<?php

declare(strict_types=1);

namespace App\Entity;

interface ProductInterface
{
    public function getId(): ?int;

    public function getName(): ?string;

    public function setName(string $name): Product;

    public function getPrice(): ?float;

    public function setPrice(float $price): Product;

    public function toArray(): array;
}
