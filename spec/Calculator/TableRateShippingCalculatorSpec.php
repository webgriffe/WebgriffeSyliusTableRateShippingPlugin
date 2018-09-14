<?php

namespace spec\Webgriffe\SyliusTableRateShippingPlugin\Calculator;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use PhpSpec\ObjectBehavior;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;

class TableRateShippingCalculatorSpec extends ObjectBehavior
{
    function it_calculates_the_rate_based_on_the_table_rate(
        ShipmentInterface $shipment,
        OrderInterface $order,
        ChannelInterface $channel,
        RepositoryInterface $tableRateRepository,
        ShippingTableRate $tableRate
    ): void {
        $this->beConstructedWith($tableRateRepository);

        $shipment->getShippingWeight()->willReturn(15.5);

        $shipment->getOrder()->willReturn($order);
        $order->getChannel()->willReturn($channel);
        $channel->getCode()->willReturn('CHANNEL_CODE');

        $tableRateRepository->findOneBy(['code' => 'TABLE_RATE_CODE'])->willReturn($tableRate);

        $tableRate->getRate(15.5)->willReturn(1000);

        $this
            ->calculate($shipment, ['CHANNEL_CODE' => ['table_rate_code' => 'TABLE_RATE_CODE']])
            ->shouldReturn(1000);
        ;
    }
}
