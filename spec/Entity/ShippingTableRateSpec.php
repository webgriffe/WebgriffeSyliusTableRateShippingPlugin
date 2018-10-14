<?php

declare(strict_types=1);

namespace spec\Webgriffe\SyliusTableRateShippingPlugin\Entity;

use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Webgriffe\SyliusTableRateShippingPlugin\Exception\RateNotFoundException;

class ShippingTableRateSpec extends ObjectBehavior
{
    function it_has_rates_for_a_given_weight_limit(): void
    {
        $this->addRate(20.0, 10);
        $this->addRate(5.0, 5);
        $this->addRate(10.0, 7);

        $this->getRate(2.0)->shouldReturn(5);
        $this->getRate(5.0)->shouldReturn(5);
        $this->getRate(7.0)->shouldReturn(7);
        $this->getRate(10.0)->shouldReturn(7);
        $this->getRate(15.0)->shouldReturn(10);
        $this->getRate(20.0)->shouldReturn(10);
        $this->shouldThrow(RateNotFoundException::class)->during('getRate', [25.0]);
    }
}
