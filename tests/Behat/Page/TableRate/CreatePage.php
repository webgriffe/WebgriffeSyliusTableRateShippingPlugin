<?php
declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    public function fillCode(string $code)
    {
        $this->getDocument()->fillField('Code', $code);
    }

    public function fillName(string $name)
    {
        $this->getDocument()->fillField('Name', $name);
    }
}
