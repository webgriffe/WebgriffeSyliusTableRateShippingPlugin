<?php

namespace spec\Webgriffe\SyliusTableRateShippingPlugin\Checker;

use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Checker\ShippingMethodEligibilityCheckerInterface;
use Sylius\Component\Shipping\Model\ShippingMethodInterface;
use PhpSpec\ObjectBehavior;
use Webgriffe\SyliusTableRateShippingPlugin\Calculator\TableRateShippingCalculator;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webgriffe\SyliusTableRateShippingPlugin\Exception\RateNotFoundException;
use Webgriffe\SyliusTableRateShippingPlugin\Resolver\TableRateResolverInterface;

class TableRateShippingMethodEligibilityCheckerSpec extends ObjectBehavior
{
    function let(
        ShippingMethodEligibilityCheckerInterface $eligibilityChecker,
        TableRateResolverInterface $tableRateResolver
    ): void {
        $this->beConstructedWith($eligibilityChecker, $tableRateResolver);
    }

    function it_returns_false_if_the_decorated_checker_returns_false(
        ShippingMethodEligibilityCheckerInterface $eligibilityChecker,
        ShipmentInterface $shipment,
        ShippingMethodInterface $shippingMethod
    ): void {
        $eligibilityChecker->isEligible($shipment, $shippingMethod)->willReturn(false);

        $this->isEligible($shipment, $shippingMethod)->shouldReturn(false);
    }

    function it_returns_true_if_the_decorated_checker_returns_true_and_other_calculator_is_used(
        ShippingMethodEligibilityCheckerInterface $eligibilityChecker,
        ShipmentInterface $shipment,
        ShippingMethodInterface $shippingMethod
    ): void {
        $eligibilityChecker->isEligible($shipment, $shippingMethod)->willReturn(true);

        $shippingMethod->getCalculator()->willReturn('ananas');

        $this->isEligible($shipment, $shippingMethod)->shouldReturn(true);
    }

    function it_returns_true_if_the_decorated_checker_returns_true_and_a_rate_can_be_found(
        ShippingMethodEligibilityCheckerInterface $eligibilityChecker,
        ShipmentInterface $shipment,
        ShippingMethodInterface $shippingMethod,
        TableRateResolverInterface $tableRateResolver,
        ShippingTableRate $tableRate
    ): void {
        $eligibilityChecker->isEligible($shipment, $shippingMethod)->willReturn(true);
        $shippingMethod->getCalculator()->willReturn(TableRateShippingCalculator::TYPE);
        $shippingMethod->getConfiguration()->willReturn(['some' => 'config']);
        $shipment->getShippingWeight()->willReturn(15.5);
        $tableRateResolver->resolve($shipment, ['some' => 'config'])->willReturn($tableRate);
        $tableRate->getRate(15.5)->willReturn(1000);

        $this->isEligible($shipment, $shippingMethod)->shouldReturn(true);
    }

    function it_returns_false_if_the_decorated_checker_returns_true_and_a_rate_cannot_be_found(
        ShippingMethodEligibilityCheckerInterface $eligibilityChecker,
        ShipmentInterface $shipment,
        ShippingMethodInterface $shippingMethod,
        TableRateResolverInterface $tableRateResolver,
        ShippingTableRate $tableRate
    ): void {
        $eligibilityChecker->isEligible($shipment, $shippingMethod)->willReturn(true);
        $shippingMethod->getCalculator()->willReturn(TableRateShippingCalculator::TYPE);
        $shippingMethod->getConfiguration()->willReturn(['some' => 'config']);
        $shipment->getShippingWeight()->willReturn(15.5);
        $tableRateResolver->resolve($shipment, ['some' => 'config'])->willReturn($tableRate);
        $tableRate->getRate(15.5)->willThrow(RateNotFoundException::class);

        $this->isEligible($shipment, $shippingMethod)->shouldReturn(false);
    }
}
