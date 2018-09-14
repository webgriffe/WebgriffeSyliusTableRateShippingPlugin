<?php

namespace spec\Webgriffe\SyliusTableRateShippingPlugin\Checker;

use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Checker\ShippingMethodEligibilityCheckerInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Sylius\Component\Shipping\Model\ShippingMethodInterface;
use PhpSpec\ObjectBehavior;
use Webgriffe\SyliusTableRateShippingPlugin\Calculator\TableRateShippingCalculator;

class TableRateShippingMethodEligibilityCheckerSpec extends ObjectBehavior
{
    function let(
        ShippingMethodEligibilityCheckerInterface $eligibilityChecker,
        CalculatorInterface $tableRateCalculator
    ): void {
        $this->beConstructedWith($eligibilityChecker, $tableRateCalculator);
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

    function it_returns_true_if_the_decorated_checker_returns_true_and_table_rate_calculator_works(
        ShippingMethodEligibilityCheckerInterface $eligibilityChecker,
        ShipmentInterface $shipment,
        ShippingMethodInterface $shippingMethod,
        CalculatorInterface $tableRateCalculator
    ): void {
        $eligibilityChecker->isEligible($shipment, $shippingMethod)->willReturn(true);

        $shippingMethod->getCalculator()->willReturn(TableRateShippingCalculator::TYPE);
        $shippingMethod->getConfiguration()->willReturn(['some' => 'config']);

        $tableRateCalculator->calculate($shipment, ['some' => 'config'])->willReturn(true);

        $this->isEligible($shipment, $shippingMethod)->shouldReturn(true);
    }

    function it_returns_false_if_the_decorated_checker_returns_true_and_table_rate_calculator_throws_an_exception(
        ShippingMethodEligibilityCheckerInterface $eligibilityChecker,
        ShipmentInterface $shipment,
        ShippingMethodInterface $shippingMethod,
        CalculatorInterface $tableRateCalculator
    ): void {
        $eligibilityChecker->isEligible($shipment, $shippingMethod)->willReturn(true);

        $shippingMethod->getCalculator()->willReturn(TableRateShippingCalculator::TYPE);
        $shippingMethod->getConfiguration()->willReturn(['some' => 'config']);

        $tableRateCalculator->calculate($shipment, ['some' => 'config'])->willThrow(\Exception::class);

        $this->isEligible($shipment, $shippingMethod)->shouldReturn(false);
    }
}
