<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Behaviour\ChecksCodeImmutability;
use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;
use Webmozart\Assert\Assert;

final class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    use ChecksCodeImmutability;

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            CreatePage::getCreateUpdatePageDefinedElements(),
        );
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
        $item->fillField('Rate', number_format($rate, 2, '.', ''));
    }

    /**
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    protected function getCodeElement(): NodeElement
    {
        return $this->getElement('code');
    }

    /**
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function isCurrencyDisabled(): bool
    {
        return $this->getElement('currency')->getAttribute('disabled') === 'disabled';
    }
}
