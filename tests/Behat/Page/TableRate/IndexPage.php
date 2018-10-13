<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate;

use Sylius\Behat\Page\Admin\Crud\IndexPage as BaseIndexPage;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;

class IndexPage extends BaseIndexPage implements IndexPageInterface
{
    public function getTableRateRatesCount(ShippingTableRate $shippingTableRate): int
    {
        $tableAccessor = $this->getTableAccessor();
        $table = $this->getElement('table');

        $row = $tableAccessor->getRowWithFields($table, ['code' => $shippingTableRate->getCode()]);
        $rates = $tableAccessor->getFieldFromRow($table, $row, 'rates_count');

        return (int) $rates->getText();
    }

    public function getValidationMessage(): string
    {
        return $this->getDocument()->find('css', '.sylius-flash-message.negative')->getText();
    }
}
