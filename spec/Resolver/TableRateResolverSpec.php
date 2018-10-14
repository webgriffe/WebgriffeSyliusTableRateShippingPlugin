<?php

namespace spec\Webgriffe\SyliusTableRateShippingPlugin\Resolver;

use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webgriffe\SyliusTableRateShippingPlugin\Resolver\TableRateResolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Webgriffe\SyliusTableRateShippingPlugin\Resolver\TableRateResolverInterface;

class TableRateResolverSpec extends ObjectBehavior
{
    function let(RepositoryInterface $tableRateRepository): void
    {
        $this->beConstructedWith($tableRateRepository);
    }

    function it_should_implement_table_rate_resolver_interface(): void
    {
        $this->shouldImplement(TableRateResolverInterface::class);
    }

    function it_should_resolve_table_rate_from_shipment(
        ShipmentInterface $shipment,
        OrderInterface $order,
        ChannelInterface $channel,
        RepositoryInterface $tableRateRepository,
        ShippingTableRate $tableRate
    ): void {
        $shipment->getOrder()->willReturn($order);
        $order->getChannel()->willReturn($channel);
        $channel->getCode()->willReturn('CHANNEL_CODE');
        $tableRate->getCode()->willReturn('TABLE_RATE_CODE');
        $tableRateRepository->findOneBy(['code' => 'TABLE_RATE_CODE'])->willReturn($tableRate);
        $calculatorConfig = ['CHANNEL_CODE' => ['table_rate' => $tableRate]];

        $this->resolve($shipment, $calculatorConfig)->shouldReturn($tableRate);
    }

    function it_throws_a_missing_channel_configuration_exception_if_the_order_channel_is_not_configured(
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
            ->shouldThrow(
                new MissingChannelConfigurationException(
                    'Shipping method "Table rate based" has no configuration for channel "Another channel".'
                )
            )
            ->during('resolve', [$shipment, ['CHANNEL_CODE' => ['table_rate' => $shippingTableRate]]])
        ;
    }
}
