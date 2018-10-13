<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Calculator;

use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface as BaseShipmentInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webgriffe\SyliusTableRateShippingPlugin\Form\Type\Shipping\Calculator\TableRateConfigurationType;
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

        $order = $shipment->getOrder();
        if (null === $order) {
            throw new \RuntimeException('Cannot calculate shipment cost, there\'s no order for this shipment.');
        }
        $channel = $order->getChannel();
        if (null === $channel) {
            throw new \RuntimeException(
                'Cannot calculate shipment cost, there\'s no channel for this shipment\'s order.'
            );
        }
        $channelCode = $channel->getCode();

        if (!isset($configuration[$channelCode])) {
            $shippingMethod = $shipment->getMethod();
            if (null === $shippingMethod) {
                throw new MissingChannelConfigurationException(
                    sprintf(
                        'This shipment has no configuration for channel "%s".',
                        $channel->getName()
                    )
                );
            }

            throw new MissingChannelConfigurationException(
                sprintf(
                    'Shipping method "%s" has no configuration for channel "%s".',
                    $shippingMethod->getName(),
                    $channel->getName()
                )
            );
        }

        $tableRate = $configuration[$channelCode][TableRateConfigurationType::TABLE_RATE_FIELD_NAME];

        /** @var ShippingTableRate $tableRate */
        $tableRate = $this->tableRateRepository->findOneBy(['code' => $tableRate->getCode()]);

        return $tableRate->getRate($shipment->getShippingWeight());
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
