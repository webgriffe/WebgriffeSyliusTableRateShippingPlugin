<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;

final class ShippingTableRateContext implements Context
{
    /** @var FactoryInterface */
    private $shippingTableRateFactory;

    /** @var RepositoryInterface */
    private $shippingTableRateRepository;

    public function __construct(
        FactoryInterface $shippingTableRateFactory,
        RepositoryInterface $shippingTableRateRepository
    ) {
        $this->shippingTableRateFactory = $shippingTableRateFactory;
        $this->shippingTableRateRepository = $shippingTableRateRepository;
    }

    /**
     * @Given the store has a shipping table rate :name for currency :currency
     */
    public function theStoreHasShippingTableRateForCurrency(string $name, CurrencyInterface $currency): void
    {
        /** @var ShippingTableRate $shippingTableRate */
        $shippingTableRate = $this->shippingTableRateFactory->createNew();
        $shippingTableRate->setName($name);
        $shippingTableRate->setCode(StringInflector::nameToUppercaseCode($name));
        $shippingTableRate->setCurrency($currency);

        $this->shippingTableRateRepository->add($shippingTableRate);
    }
}
