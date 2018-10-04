<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;

final class NumberContext implements Context
{
    private const STRING_TO_INT = [
        'zero' => 0,
        'one' => 1,
        'two' => 2,
        'three' => 3,
    ];

    /**
     * @Transform :number
     */
    public function transformNumber(string $number): int
    {
        if (is_numeric($number)) {
            return (int) $number;
        }
        if (!\array_key_exists($number, self::STRING_TO_INT)) {
            throw new \RuntimeException("Can't transform the number '$number' to integer. It's to big.");
        }

        return self::STRING_TO_INT[$number];
    }
}
