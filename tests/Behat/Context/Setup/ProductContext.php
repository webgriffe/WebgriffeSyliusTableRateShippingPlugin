<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductContext implements Context
{
    /** @var ExampleFactoryInterface */
    private $productExampleFactory;

    /** @var RepositoryInterface */
    private $productRepository;

    public function __construct(
        ExampleFactoryInterface $productExampleFactory,
        RepositoryInterface $productRepository
    ) {
        $this->productExampleFactory = $productExampleFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * @Transform /^(\-)?(?:€|£|￥|\$)((?:\d+\.)?\d+)$/
     */
    public function getPriceFromString(string $sign, string $price): int
    {
        if (!(bool) preg_match('/^\d+(?:\.\d{1,2})?$/', $price)) {
            throw new \InvalidArgumentException('Price string should not have more than 2 decimal digits.');
        }

        $price = (int) round((float) $price * 100, 2);

        if ('-' === $sign) {
            $price *= -1;
        }

        return $price;
    }

    /**
     * @Transform :weight
     */
    public function transformWeight(string $weight): int
    {
        return (int) $weight;
    }

    /**
     * @Given the store has a product :productName which weights :weight kg
     */
    public function theStoreHasProductPricedWhichWeights(
        string $productName,
        int $weight
    ): void {
        /** @var ProductInterface $product */
        $product = $this->productExampleFactory->create([
            'name' => $productName,
            'enabled' => true,
            'main_taxon' => null,
        ]);

        /** @var ProductVariantInterface $productVariant */
        $productVariant = $product->getVariants()->first();

        $productVariant->setWeight((float) $weight);

        $this->productRepository->add($product);
    }
}
