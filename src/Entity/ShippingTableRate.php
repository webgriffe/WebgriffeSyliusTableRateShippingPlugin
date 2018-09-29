<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Entity;

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

    /** @var array */
    private $weightLimitToRate = [];

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

    public function addRate(float $weightLimit, int $rate): void
    {
        $this->weightLimitToRate[] = ['weightLimit' => $weightLimit, 'rate' => $rate];
    }

    public function getRate(float $weight): int
    {
        usort($this->weightLimitToRate, function (array $a, array $b): int {
            return $a['weightLimit'] <=> $b['weightLimit'];
        });

        foreach ($this->weightLimitToRate as $array) {
            if ($weight <= $array['weightLimit']) {
                return $array['rate'];
            }
        }

        throw new \RuntimeException('No rate found for given weight!');
    }

    public function getRatesCount(): int
    {
        return count($this->weightLimitToRate);
    }

    /**
     * @return array
     * @internal
     */
    public function getWeightLimitToRate(): array
    {
        return $this->weightLimitToRate;
    }

    /**
     * @param array $weightLimitToRate
     * @internal
     */
    public function setWeightLimitToRate(array $weightLimitToRate): void
    {
        $this->weightLimitToRate = $weightLimitToRate;
    }
}
