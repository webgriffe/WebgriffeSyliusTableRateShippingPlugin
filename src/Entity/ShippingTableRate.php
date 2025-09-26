<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Entity;

use Sylius\Component\Currency\Model\CurrencyInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Webgriffe\SyliusTableRateShippingPlugin\Exception\RateNotFoundException;

/**
 * @UniqueEntity("code", groups={"sylius"})
 *
 * @psalm-api
 */
class ShippingTableRate implements ShippingTableRateInterface
{
    protected ?int $id = null;

    /** @Assert\NotBlank(groups={"sylius"}) */
    protected ?string $code = null;

    /** @Assert\NotBlank(groups={"sylius"}) */
    protected ?string $name = null;

    /** @Assert\NotBlank(groups={"sylius"}) */
    protected ?CurrencyInterface $currency = null;

    /**
     * @var array<array{weightLimit: float, rate: int}>
     *
     * @Assert\NotBlank(
     *     groups={"sylius"},
     *     message="webgriffe_sylius_table_rate_plugin.ui.shipping_table_rate.weightLimitToRate.not_blank"
     * )
     */
    protected array $weightLimitToRate = [];

    #[\Override]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[\Override]
    public function getCode(): ?string
    {
        return $this->code;
    }

    #[\Override]
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    #[\Override]
    public function getName(): ?string
    {
        return $this->name;
    }

    #[\Override]
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    #[\Override]
    public function getCurrency(): ?CurrencyInterface
    {
        return $this->currency;
    }

    #[\Override]
    public function setCurrency(?CurrencyInterface $currency): void
    {
        $this->currency = $currency;
    }

    #[\Override]
    public function addRate(float $weightLimit, int $rate): void
    {
        $this->weightLimitToRate[] = ['weightLimit' => $weightLimit, 'rate' => $rate];
    }

    #[\Override]
    public function getRate(float $weight): int
    {
        usort($this->weightLimitToRate, static function (array $a, array $b): int {
            return $a['weightLimit'] <=> $b['weightLimit'];
        });

        foreach ($this->weightLimitToRate as $array) {
            if ($weight <= $array['weightLimit']) {
                return $array['rate'];
            }
        }

        throw new RateNotFoundException($this, $weight);
    }

    #[\Override]
    public function getRatesCount(): int
    {
        return count($this->weightLimitToRate);
    }

    /**
     * @internal
     */
    public function getWeightLimitToRate(): array
    {
        return $this->weightLimitToRate;
    }

    /**
     * @internal
     *
     * @param array<array{weightLimit: float, rate: int}> $weightLimitToRate
     */
    public function setWeightLimitToRate(array $weightLimitToRate): void
    {
        $this->weightLimitToRate = $weightLimitToRate;
    }
}
