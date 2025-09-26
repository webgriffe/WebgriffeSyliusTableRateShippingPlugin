<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Webmozart\Assert\Assert;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    public static function getCreateUpdatePageDefinedElements(): array
    {
        return [
            'form' => 'form[name="webgriffe_sylius_table_rate_plugin_shipping_table_rate"]',
            'code' => '#webgriffe_sylius_table_rate_plugin_shipping_table_rate_code',
            'name' => '#webgriffe_sylius_table_rate_plugin_shipping_table_rate_name',
            'currency' => '#webgriffe_sylius_table_rate_plugin_shipping_table_rate_currency',
            'weightLimitToRate' => '#webgriffe_sylius_table_rate_plugin_shipping_table_rate_weightLimitToRate',
        ];
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            self::getCreateUpdatePageDefinedElements(),
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
        $text = $this->getElement('form')->find('css', '.sylius-validation-error')?->getText();
        if ($text === null) {
            return '';
        }

        return trim($text);
    }

    public function addRate(int $rate, int $weightLimit): void
    {
        $weightLimitToRateField = $this->getDocument()->findById(
            'webgriffe_sylius_table_rate_plugin_shipping_table_rate_weightLimitToRate',
        );
        Assert::notNull($weightLimitToRateField, 'Weight limit to rate field not found on the page');

        $addRateButton = $weightLimitToRateField->findLink('Add');
        Assert::notNull($addRateButton, 'Add rate button not found on the page');

        $addRateButton->click();

        $item = $weightLimitToRateField->find('css', '[data-form-collection=item]:last-child');
        Assert::notNull($item, 'Added rate item not found on the page');

        $item->fillField('Weight limit', (string) $weightLimit);
        $value = $rate / 100;
        $item->fillField('Rate', number_format($value, 2, '.', ''));
    }
}
