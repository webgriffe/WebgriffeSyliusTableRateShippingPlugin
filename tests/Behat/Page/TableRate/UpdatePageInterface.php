<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate;

use Sylius\Behat\Page\Admin\Crud\UpdatePageInterface as BaseUpdatePageInterface;

interface UpdatePageInterface extends BaseUpdatePageInterface
{
    public function addRate(int $rate, int $weightLimit);

    public function isCodeDisabled();

    public function isCurrencyDisabled(): bool;
}
