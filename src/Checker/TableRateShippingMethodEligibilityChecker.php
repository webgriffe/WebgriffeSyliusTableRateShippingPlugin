<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Checker;

use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Shipping\Checker\Eligibility\ShippingMethodEligibilityCheckerInterface;
use Sylius\Component\Shipping\Model\ShippingMethodInterface;
use Sylius\Component\Shipping\Model\ShippingSubjectInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Calculator\TableRateShippingCalculator;
use Webgriffe\SyliusTableRateShippingPlugin\Exception\RateNotFoundException;
use Webgriffe\SyliusTableRateShippingPlugin\Resolver\TableRateResolverInterface;
use Webmozart\Assert\Assert;

final class TableRateShippingMethodEligibilityChecker implements ShippingMethodEligibilityCheckerInterface
{
    public function __construct(
        private ShippingMethodEligibilityCheckerInterface $eligibilityChecker,
        private TableRateResolverInterface $tableRateResolver,
    ) {
    }

    #[\Override]
    public function isEligible(
        ShippingSubjectInterface $shippingSubject,
        ShippingMethodInterface $shippingMethod,
    ): bool {
        if (!$this->eligibilityChecker->isEligible($shippingSubject, $shippingMethod)) {
            return false;
        }

        if ($shippingMethod->getCalculator() !== TableRateShippingCalculator::TYPE) {
            return true;
        }

        Assert::isInstanceOf($shippingSubject, ShipmentInterface::class);

        $weight = $shippingSubject->getShippingWeight();
        $tableRate = $this->tableRateResolver->resolve($shippingSubject, $shippingMethod->getConfiguration());

        try {
            $tableRate->getRate($weight);
        } catch (RateNotFoundException $e) {
            return false;
        }

        return true;
    }
}
