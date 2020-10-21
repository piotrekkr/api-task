<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use Symfony\Component\HttpFoundation\Request;

trait GetRequestMockTrait
{
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
}
