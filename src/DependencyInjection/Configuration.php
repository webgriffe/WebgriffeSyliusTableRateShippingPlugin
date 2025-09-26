<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @psalm-api
 */
final class Configuration implements ConfigurationInterface
{
    #[\Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('webgriffe_sylius_table_rate_shipping_plugin');

        return $treeBuilder;
    }
}
