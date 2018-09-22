<?php
declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ShippingTableRateType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class)
            ->add('name', TextType::class)
        ;
    }

    public function getBlockPrefix(): ?string
    {
        return 'webgriffe_sylius_table_rate_plugin_shipping_table_rate';
    }
}
