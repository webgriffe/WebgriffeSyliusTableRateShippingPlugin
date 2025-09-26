<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @psalm-api
 */
final class WebgriffeSyliusTableRateShippingPlugin extends Bundle
{
    use SyliusPluginTrait;

    #[\Override]
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
