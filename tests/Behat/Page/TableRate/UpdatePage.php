<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Behaviour\ChecksCodeImmutability;
use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;

final class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    use ChecksCodeImmutability;

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            [
                'form' => 'form[name="webgriffe_sylius_table_rate_plugin_shipping_table_rate"]',
                'code' => '[data-test-code]',
                'name' => '[data-test-name]',
                'currency' => '[data-test-currency]',
                'weightlimittorates' => '[data-test-weightlimittorate]',
                'last_weightlimittorate' => '[data-test-weightlimittorate] [data-test-weightlimittorate]:last-child',
                'add_weightlimittorate' => '[data-test-add-weightlimittorate]',
            ],
        );
    }

    protected function getCodeElement(): NodeElement
    {
        return $this->getElement('code');
    }

    public function isCurrencyDisabled(): bool
    {
        return $this->getElement('currency')->getAttribute('disabled') === 'disabled';
    }

    public function addRate(int $rate, int $weightLimit): void
    {
        $count = count($this->getWeightLimitToRates());
        $this->getElement('add_weightlimittorate')->click();
        $this->getDocument()->waitFor(5, fn () => $count + 1 === count($this->getWeightLimitToRates()));

        $weightLimitToRate = $this->getElement('last_weightlimittorate');
        $weightLimitToRate->fillField('Weight limit', (string) $weightLimit);
        $value = $rate / 100;
        $weightLimitToRate->fillField('Rate', number_format($value, 2, '.', ''));
    }

    protected function getWeightLimitToRates(): array
    {
        return $this->getElement('weightlimittorates')->findAll('css', '[data-test-weightlimittorate]');
    }
}
