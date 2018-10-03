<?php
declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\ShippingMethod;

use Sylius\Behat\Page\Admin\ShippingMethod\UpdatePageInterface as BaseUpdatePageInterface;

interface UpdatePageInterface extends BaseUpdatePageInterface
{
    public function getTableRateOptions(string $channelCode): array;
}
