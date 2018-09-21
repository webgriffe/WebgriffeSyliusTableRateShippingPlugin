<?php
declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Page\TableRate\IndexPageInterface;

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
     * @Then I should see zero table rates in the list
     */
    public function iShouldSeeZeroTableRatesInTheList()
    {
        throw new PendingException();
    }
}
