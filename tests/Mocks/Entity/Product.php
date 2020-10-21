<?php

declare(strict_types=1);

namespace App\Tests\Mocks\Entity;

class Product extends \App\Entity\Product
{
    public function __construct(?int $id = null)
    {
        $this->id = $id;
    }
}
