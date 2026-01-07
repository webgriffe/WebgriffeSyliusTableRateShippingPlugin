<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Sylius\Component\Currency\Model\CurrencyInterface;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
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

    public function fillCode(string $code): void
    {
        $this->getDocument()->fillField('Code', $code);
    }

    public function fillName(string $name): void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    public function fillCurrency(?CurrencyInterface $currency): void
    {
        $code = $currency?->getCode();
        $this->getDocument()->selectFieldOption('Currency', $code !== null ? $code : '');
    }

    public function getFormValidationMessage(): string
    {
        $text = $this->getElement('form')->find('css', '.alert-danger')?->getText();
        if ($text === null) {
            return '';
        }

        return trim($text);
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
