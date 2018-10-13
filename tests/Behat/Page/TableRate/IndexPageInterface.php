<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate;

use Sylius\Behat\Page\Admin\Crud\IndexPageInterface as BaseIndexPageInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;

interface IndexPageInterface extends BaseIndexPageInterface
{
    public function getTableRateRatesCount(ShippingTableRate $shippingTableRate): int;

    public function getValidationMessage(): string;
}
