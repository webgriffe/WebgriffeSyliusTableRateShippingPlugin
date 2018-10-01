<?php
declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    public function fillCode(string $code);
    public function fillName(string $name);
    public function fillCurrency(CurrencyInterface $currency);
}
