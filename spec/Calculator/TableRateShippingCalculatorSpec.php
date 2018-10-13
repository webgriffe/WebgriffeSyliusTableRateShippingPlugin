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

class TableRateShippingCalculatorSpec extends ObjectBehavior
{
    function let(RepositoryInterface $tableRateRepository): void
    {
        $this->beConstructedWith($tableRateRepository);
    }

    function it_calculates_the_rate_based_on_the_table_rate(
        ShipmentInterface $shipment,
        OrderInterface $order,
        ChannelInterface $channel,
        RepositoryInterface $tableRateRepository,
        ShippingTableRate $tableRate
    ): void {
        $shipment->getShippingWeight()->willReturn(15.5);

        $shipment->getOrder()->willReturn($order);
        $order->getChannel()->willReturn($channel);
        $channel->getCode()->willReturn('CHANNEL_CODE');
        $tableRate->getCode()->willReturn('TABLE_RATE_CODE');

        $tableRateRepository->findOneBy(['code' => 'TABLE_RATE_CODE'])->willReturn($tableRate);

        $tableRate->getRate(15.5)->willReturn(1000);

        $this
            ->calculate($shipment, ['CHANNEL_CODE' => ['table_rate' => $tableRate]])
            ->shouldReturn(1000)
        ;
    }

    function it_throws_an_missing_channel_configuration_exception_if_the_order_channel_is_not_configured(
        ShipmentInterface $shipment,
        OrderInterface $order,
        ChannelInterface $channel,
        ShippingMethodInterface $shippingMethod,
        ShippingTableRate $shippingTableRate
    ): void {
        $shipment->getOrder()->willReturn($order);
        $order->getChannel()->willReturn($channel);
        $channel->getCode()->willReturn('ANOTHER_CHANNEL_CODE');
        $channel->getName()->willReturn('Another channel');

        $shipment->getMethod()->willReturn($shippingMethod);
        $shippingMethod->getName()->willReturn('Table rate based');
        $shippingTableRate->getCode()->willReturn('TABLE_RATE_CODE');

        $this
            ->shouldThrow(new MissingChannelConfigurationException('Shipping method "Table rate based" has no configuration for channel "Another channel".'))
            ->during('calculate', [$shipment, ['CHANNEL_CODE' => ['table_rate' => $shippingTableRate]]])
        ;
    }
}
