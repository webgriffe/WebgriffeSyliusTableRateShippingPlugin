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
    /**
     * @var TableRateResolverInterface
     */
    private $tableRateResolver;

    public function __construct(TableRateResolverInterface $tableRateResolver)
    {
        $this->tableRateResolver = $tableRateResolver;
    }

    public function calculate(BaseShipmentInterface $shipment, array $configuration): int
    {
        /** @var ShipmentInterface $shipment */
        Assert::isInstanceOf($shipment, ShipmentInterface::class);

        $tableRate = $this->tableRateResolver->resolve($shipment, $configuration);

        try {
            return $tableRate->getRate($shipment->getShippingWeight());
        } catch (RateNotFoundException $e) {
            return 0;
        }
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
