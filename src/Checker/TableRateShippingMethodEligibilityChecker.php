<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Checker;

use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Checker\ShippingMethodEligibilityCheckerInterface;
use Sylius\Component\Shipping\Model\ShippingMethodInterface;
use Sylius\Component\Shipping\Model\ShippingSubjectInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Calculator\TableRateShippingCalculator;

final class TableRateShippingMethodEligibilityChecker implements ShippingMethodEligibilityCheckerInterface
{
    /** @var ShippingMethodEligibilityCheckerInterface */
    private $eligibilityChecker;

    /** @var CalculatorInterface */
    private $tableRateCalculator;

    public function __construct(
        ShippingMethodEligibilityCheckerInterface $eligibilityChecker,
        CalculatorInterface $tableRateCalculator
    ) {
        $this->eligibilityChecker = $eligibilityChecker;
        $this->tableRateCalculator = $tableRateCalculator;
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

        try {
            $this->tableRateCalculator->calculate($subject, $method->getConfiguration());
        } catch (\Throwable $throwable) {
            // TODO: SPECIFY THE EXCEPTION CLASS

            return false;
        }

        return true;
    }
}
