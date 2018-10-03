<?php
declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Element\NodeElement;
use Sylius\Component\Core\Model\ChannelInterface;
use Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\ShippingMethod\UpdatePageInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webmozart\Assert\Assert;

final class ManagingShippingMethodsWithTableRateContext implements Context
{
    /**
     * @var UpdatePageInterface
     */
    private $updatePage;

    public function __construct(UpdatePageInterface $updatePage)
    {
        $this->updatePage = $updatePage;
    }

    /**
     * @Then I should be able to choose only the table rate :shippingTableRate for the :channel channel
     */
    public function iShouldBeAbleToChooseOnlyTheTableRateForTheChannel(
        ShippingTableRate $shippingTableRate,
        ChannelInterface $channel
    ) {
        /** @var NodeElement[] $options */
        $options = $this->updatePage->getTableRateOptions($channel->getCode());
        Assert::count($options, 1);
        Assert::eq($options[0]->getAttribute('value'), $shippingTableRate->getCode());
    }
}
