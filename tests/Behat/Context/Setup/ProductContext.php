<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

final class ProductContext implements Context
{
    /**
     * @Transform /^(\-)?(?:€|£|￥|\$)((?:\d+\.)?\d+)$/
     */
    public function getPriceFromString(string $sign, string $price): int
    {
        if (!(bool) preg_match('/^\d+(?:\.\d{1,2})?$/', $price)) {
            throw new \InvalidArgumentException('Price string should not have more than 2 decimal digits.');
        }

        $price = (int) round((float) $price * 100, 2);

        if ('-' === $sign) {
            $price *= -1;
        }

        return $price;
    }

    /**
     * @Transform :weight
     */
    public function transformWeight(string $weight): int
    {
        return (int) $weight;
    }

    /**
     * @Given the store has a product :productName priced at :money which weights :weight kg
     */
    public function theStoreHasProductPricedWhichWeights(
        string $productName,
        int $money,
        int $weight
    ): void {
        throw new PendingException();
    }
}
