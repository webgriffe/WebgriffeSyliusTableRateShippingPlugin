<?php

namespace spec\Webgriffe\SyliusTableRateShippingPlugin\Calculator;

use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use PhpSpec\ObjectBehavior;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webgriffe\SyliusTableRateShippingPlugin\Exception\RateNotFoundException;
use Webgriffe\SyliusTableRateShippingPlugin\Resolver\TableRateResolverInterface;

class TableRateShippingCalculatorSpec extends ObjectBehavior
{
    function let(TableRateResolverInterface $tableRateResolver): void
    {
        $this->beConstructedWith($tableRateResolver);
    }

    function it_calculates_the_rate_based_on_the_table_rate(
        ShipmentInterface $shipment,
        TableRateResolverInterface $tableRateResolver,
        ShippingTableRate $tableRate
    ): void {
        $configuration = ['CHANNEL_CODE' => ['table_rate' => $tableRate]];
        $shipment->getShippingWeight()->willReturn(15.5);
        $tableRateResolver->resolve($shipment, $configuration)->willReturn($tableRate);
        $tableRate->getRate(15.5)->willReturn(1000);

        $this
            ->calculate($shipment, $configuration)
            ->shouldReturn(1000)
        ;
    }

    function it_should_return_zero_if_no_rate_is_found_for_a_given_shipment(
        ShipmentInterface $shipment,
        TableRateResolverInterface $tableRateResolver,
        ShippingTableRate $tableRate
    ) {
        $configuration = ['CHANNEL_CODE' => ['table_rate' => $tableRate]];
        $shipment->getShippingWeight()->willReturn(1000);
        $tableRateResolver->resolve($shipment, $configuration)->willReturn($tableRate);
        $tableRate->getRate(1000)->willThrow(RateNotFoundException::class);

        $this
            ->calculate($shipment, $configuration)
            ->shouldReturn(0)
        ;
    }
}
