<?php
declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate\CreatePageInterface;
use Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate\IndexPageInterface;
use Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate\UpdatePageInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webmozart\Assert\Assert;

class ManagingTableRatesContext implements Context
{
    /**
     * @var IndexPageInterface
     */
    private $indexPage;
    /**
     * @var CreatePageInterface
     */
    private $createPage;
    /**
     * @var UpdatePageInterface
     */
    private $updatePage;

    public function __construct(
        IndexPageInterface $indexPage,
        CreatePageInterface $createPage,
        UpdatePageInterface $updatePage
    ) {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
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

    /**
     * @When I add a shipping table rate named :name for currency :currency
     */
    public function iAddAShippingTableRateNamedForCurrency(string $name, CurrencyInterface $currency)
    {
        $this->createPage->open();
        $this->createPage->fillCode(StringInflector::nameToUppercaseCode($name));
        $this->createPage->fillName($name);
        $this->createPage->create();
    }

    /**
     * @When I delete the :shippingTableRate table rate
     */
    public function iDeleteTheTableRate(ShippingTableRate $shippingTableRate)
    {
        $this->indexPage->deleteResourceOnPage(['name' => $shippingTableRate->getName()]);
    }

    /**
     * @Given I want to modify the :shippingTableRate table rate
     */
    public function iWantToModifyTheTableRate(ShippingTableRate $shippingTableRate): void
    {
        $this->updatePage->open(['id' => $shippingTableRate->getId()]);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I change its code to :code
     */
    public function iChangeItsCodeTo(string $code)
    {
        $this->createPage->fillCode($code);
    }

    /**
     * @Then /^(this shipping table rate) code should be "([^"]+)"$/
     */
    public function thisTableRateCodeShouldBe(ShippingTableRate $shippingTableRate, string $code)
    {
        $this->updatePage->open(['id' => $shippingTableRate->getId()]);
        $this->updatePage->hasResourceValues(['code' => $code]);
    }

    /**
     * @When /^I add a new rate of ("[^"]+") for shipments up to (\d+) kg$/
     */
    public function iAddANewRateOfForShipmentsUpToKg(int $rate, int $weightLimit): void
    {
        $this->updatePage->addRate($rate, $weightLimit);
    }

    /**
     * @Then /^(this shipping table rate) should have (\d+) rates$/
     */
    public function thisShippingTableRateShouldHaveRates(ShippingTableRate $shippingTableRate, int $count)
    {
        $this->indexPage->open();

        Assert::eq($this->indexPage->getTableRateRatesCount($shippingTableRate), $count);
    }
}
