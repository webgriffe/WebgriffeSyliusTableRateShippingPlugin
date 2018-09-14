<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Model;

use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class ShippingTableRate implements ResourceInterface
{
    /** @var int|null */
    private $id;

    /** @var string|null */
    private $code;

    /** @var string|null */
    private $name;

    /** @var CurrencyInterface|null */
    private $currency;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCurrency(): ?CurrencyInterface
    {
        return $this->currency;
    }

    public function setCurrency(?CurrencyInterface $currency): void
    {
        $this->currency = $currency;
    }
}
