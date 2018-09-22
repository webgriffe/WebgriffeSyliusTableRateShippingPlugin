<?php
declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate\IndexPageInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webmozart\Assert\Assert;

class ManagingTableRatesContext implements Context
{
    /**
     * @var IndexPageInterface
     */
    private $indexPage;

    public function __construct(IndexPageInterface $indexPage)
    {
        $this->indexPage = $indexPage;
    }

    /**
     * @When I am browsing the list of table rates
     * @throws \Sylius\Behat\Page\UnexpectedPageException
     */
    public function iAmBrowsingTheListOfTableRates(): void
    {
        $this->indexPage->open();
    }

    /**
     * @Then I should see :number table rate(s) in the list
     */
    public function iShouldSeeZeroTableRatesInTheList(int $number = 0)
    {
        Assert::same($number, $this->indexPage->countItems());
    }

    /**
     * @Then I should see the :shippingTableRate table rate in the list
     */
    public function iShouldSeeTheTableRateInTheList(ShippingTableRate $shippingTableRate)
    {
        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $shippingTableRate->getName()]));
    }
}
