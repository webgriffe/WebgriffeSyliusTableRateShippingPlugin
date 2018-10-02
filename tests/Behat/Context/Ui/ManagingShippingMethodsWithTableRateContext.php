<?php
declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Sylius\Component\Core\Model\ChannelInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;

final class ManagingShippingMethodsWithTableRateContext implements Context
{
    /**
     * @Then I should be able to choose only the table rate :shippingTableRate for the :channel channel
     */
    public function iShouldBeAbleToChooseOnlyTheTableRateForTheChannel(
        ShippingTableRate $shippingTableRate,
        ChannelInterface $channel
    ) {
        throw new PendingException();
    }
}
