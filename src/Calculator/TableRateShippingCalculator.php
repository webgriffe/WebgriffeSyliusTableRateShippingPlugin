<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Calculator;

use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface as BaseShipmentInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Exception\RateNotFoundException;
use Webgriffe\SyliusTableRateShippingPlugin\Resolver\TableRateResolverInterface;
use Webmozart\Assert\Assert;

final class TableRateShippingCalculator implements CalculatorInterface
{
    public const TYPE = 'table_rate';

    public function __construct(private TableRateResolverInterface $tableRateResolver)
    {
    }

    #[\Override]
    public function calculate(BaseShipmentInterface $subject, array $configuration): int
    {
        Assert::isInstanceOf($subject, ShipmentInterface::class);

        $tableRate = $this->tableRateResolver->resolve($subject, $configuration);

        try {
            return $tableRate->getRate($subject->getShippingWeight());
        } catch (RateNotFoundException $e) {
            return 0;
        }
    }

    #[\Override]
    public function getType(): string
    {
        return self::TYPE;
    }
}
