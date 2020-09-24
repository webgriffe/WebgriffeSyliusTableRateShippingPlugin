<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Checker;

use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Shipping\Checker\ShippingMethodEligibilityCheckerInterface;
use Sylius\Component\Shipping\Model\ShippingMethodInterface;
use Sylius\Component\Shipping\Model\ShippingSubjectInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Calculator\TableRateShippingCalculator;
use Webgriffe\SyliusTableRateShippingPlugin\Exception\RateNotFoundException;
use Webgriffe\SyliusTableRateShippingPlugin\Resolver\TableRateResolverInterface;
use Webmozart\Assert\Assert;

final class TableRateShippingMethodEligibilityChecker implements ShippingMethodEligibilityCheckerInterface
{
    /** @var ShippingMethodEligibilityCheckerInterface */
    private $eligibilityChecker;

    /** @var TableRateResolverInterface */
    private $tableRateResolver;

    public function __construct(
        ShippingMethodEligibilityCheckerInterface $eligibilityChecker,
        TableRateResolverInterface $tableRateResolver
    ) {
        $this->eligibilityChecker = $eligibilityChecker;
        $this->tableRateResolver = $tableRateResolver;
    }

    public function isEligible(
        ShippingSubjectInterface $subject,
        ShippingMethodInterface $method
    ): bool {
        if (!$this->eligibilityChecker->isEligible($subject, $method)) {
            return false;
        }

        if ($method->getCalculator() !== TableRateShippingCalculator::TYPE) {
            return true;
        }

        Assert::isInstanceOf($subject, ShipmentInterface::class);

        $weight = $subject->getShippingWeight();
        /** @noinspection PhpParamsInspection */
        $tableRate = $this->tableRateResolver->resolve($subject, $method->getConfiguration());

        try {
            $tableRate->getRate($weight);
        } catch (RateNotFoundException $e) {
            return false;
        }

        return true;
    }
}
