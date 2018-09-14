<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webmozart\Assert\Assert;

final class ShippingTableRateContext implements Context
{
    /** @var FactoryInterface */
    private $shippingTableRateFactory;

    /** @var RepositoryInterface */
    private $shippingTableRateRepository;

    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var ExampleFactoryInterface */
    private $shippingMethodExampleFactory;

    /** @var RepositoryInterface */
    private $shippingMethodRepository;

    public function __construct(
        FactoryInterface $shippingTableRateFactory,
        RepositoryInterface $shippingTableRateRepository,
        SharedStorageInterface $sharedStorage,
        ExampleFactoryInterface $shippingMethodExampleFactory,
        RepositoryInterface $shippingMethodRepository
    ) {
        $this->shippingTableRateFactory = $shippingTableRateFactory;
        $this->shippingTableRateRepository = $shippingTableRateRepository;
        $this->sharedStorage = $sharedStorage;
        $this->shippingMethodExampleFactory = $shippingMethodExampleFactory;
        $this->shippingMethodRepository = $shippingMethodRepository;
    }

    /**
     * @Transform
     */
    public function transformShippingTableRate(string $name): ShippingTableRate
    {
        $shippingTableRates = $this->shippingTableRateRepository->findBy(['name' => $name]);

        Assert::count(
            $shippingTableRates,
            1,
            sprintf('%d shipping table rates has been found with name "%s".', count($shippingTableRates), $name)
        );

        return $shippingTableRates[0];
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

    /**
     * @Given the store has :shippingMethodName shipping method using :shippingTableRate table rate for :channel channel
     */
    public function theStoreHasShippingMethodUsingTableRateForChannel(
        string $shippingMethodName,
        ShippingTableRate $shippingTableRate,
        ChannelInterface $channel
    ): void {
        /** @var ShippingMethodInterface $shippingMethod */
        $shippingMethod = $this->shippingMethodExampleFactory->create([
            'name' => $shippingMethodName,
            'enabled' => true,
            'zone' => $this->getShippingZone(),
            'calculator' => [
                'type' => 'table_rate',
                'configuration' => [],
            ],
            'channels' => [$channel],
        ]);

        $this->shippingMethodRepository->add($shippingMethod);
    }

    private function getShippingZone(): ZoneInterface
    {
        if ($this->sharedStorage->has('shipping_zone')) {
            return $this->sharedStorage->get('shipping_zone');
        }

        return $this->sharedStorage->get('zone');
    }
}
