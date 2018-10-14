<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Resolver;

use Sylius\Component\Core\Model\ShipmentInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;

interface TableRateResolverInterface
{
    public function resolve(ShipmentInterface $shipment, array $calculatorConfig): ShippingTableRate;
}
