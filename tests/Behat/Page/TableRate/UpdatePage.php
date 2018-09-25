<?php
declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate;

use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;

final class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    protected function getDefinedElements()
    {
        return array_merge(
            parent::getDefinedElements(),
            ['code' => '#webgriffe_sylius_table_rate_plugin_shipping_table_rate_code']
        );
    }

}
