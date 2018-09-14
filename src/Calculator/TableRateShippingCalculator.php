<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Calculator;

use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

final class TableRateShippingCalculator implements CalculatorInterface
{
    public const TYPE = 'table_rate';

    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        return 500;
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
