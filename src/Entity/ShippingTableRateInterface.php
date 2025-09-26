<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Entity;

use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @psalm-api
 */
interface ShippingTableRateInterface extends ResourceInterface, CodeAwareInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getCurrency(): ?CurrencyInterface;

    public function setCurrency(?CurrencyInterface $currency): void;

    public function addRate(float $weightLimit, int $rate): void;

    public function getRate(float $weight): int;

    public function getRatesCount(): int;
}
