<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Calculator;

use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface as BaseShipmentInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webmozart\Assert\Assert;

final class TableRateShippingCalculator implements CalculatorInterface
{
    public const TYPE = 'table_rate';

    /** @var RepositoryInterface */
    private $tableRateRepository;

    public function __construct(RepositoryInterface $tableRateRepository)
    {
        $this->tableRateRepository = $tableRateRepository;
    }

    public function calculate(BaseShipmentInterface $shipment, array $configuration): int
    {
        /** @var ShipmentInterface $shipment */
        Assert::isInstanceOf($shipment, ShipmentInterface::class);

        $channelCode = $shipment->getOrder()->getChannel()->getCode();

        if (!isset($configuration[$channelCode])) {
            throw new MissingChannelConfigurationException(sprintf(
                'Shipping method "%s" has no configuration for channel "%s".',
                $shipment->getMethod()->getName(),
                $shipment->getOrder()->getChannel()->getName()
            ));
        }

        $tableRateCode = $configuration[$channelCode]['table_rate_code'];

        /** @var ShippingTableRate $tableRate */
        $tableRate = $this->tableRateRepository->findOneBy(['code' => $tableRateCode]);

        return $tableRate->getRate($shipment->getShippingWeight());
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
