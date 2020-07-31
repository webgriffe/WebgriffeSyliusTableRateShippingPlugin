<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Shop\Checkout\AddressPageInterface;
use Sylius\Behat\Page\Shop\Checkout\SelectShippingPageInterface;
use Sylius\Component\Core\Factory\AddressFactoryInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Webmozart\Assert\Assert;

final class ShippingTableRateContext implements Context
{
    /** @var AddressFactoryInterface */
    private $addressFactory;

    /** @var AddressPageInterface */
    private $addressPage;

    /** @var SelectShippingPageInterface */
    private $selectShippingPage;

    public function __construct(
        AddressFactoryInterface $addressFactory,
        AddressPageInterface $addressPage,
        SelectShippingPageInterface $selectShippingPage
    ) {
        $this->addressFactory = $addressFactory;
        $this->addressPage = $addressPage;
        $this->selectShippingPage = $selectShippingPage;
    }

    /**
     * @Then I should have no shipping methods available to choose from
     */
    public function iShouldHaveNoShippingMethodsAvailable(): void
    {
        $this->addressPage->open();
        $this->addressPage->specifyBillingAddress($this->createDefaultAddress());
        $this->addressPage->nextStep();

        $this->selectShippingPage->verify();
        Assert::true($this->selectShippingPage->hasNoShippingMethodsMessage());
    }

    private function createDefaultAddress(): AddressInterface
    {
        /** @var AddressInterface $address */
        $address = $this->addressFactory->createNew();
        $address->setFirstName('John');
        $address->setLastName('Doe');
        $address->setCountryCode('US');
        $address->setCity('North Bridget');
        $address->setPostcode('93-554');
        $address->setStreet('0635 Myron Hollow Apt. 711');
        $address->setPhoneNumber('321123456');

        return $address;
    }
}
