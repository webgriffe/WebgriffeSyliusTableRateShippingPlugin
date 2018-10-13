<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Form\Type\Shipping\Calculator;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;

final class TableRateConfigurationType extends AbstractType
{
    public const TABLE_RATE_FIELD_NAME = 'table_rate';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $messagesNamespace = 'webgriffe_sylius_table_rate_plugin.ui.calculator_configuration.';
        $currency = $options['currency'];
        $builder->add(
            self::TABLE_RATE_FIELD_NAME,
            EntityType::class,
            [
                'label' => $messagesNamespace . 'table_rate.label',
                'placeholder' => '' . $messagesNamespace . 'table_rate.placeholder',
                'class' => ShippingTableRate::class,
                'query_builder' => function (EntityRepository $entityRepository) use ($currency): QueryBuilder {
                    return $entityRepository
                        ->createQueryBuilder('tr')
                        ->where('tr.currency = :currency')
                        ->setParameter('currency', $currency)
                    ;
                },
                'choice_label' => 'name',
                'choice_value' => 'code',
                'constraints' => [new NotBlank(['groups' => ['sylius']])],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(['data_class' => null])
            ->setRequired('currency')
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'webgriffe_sylius_table_rate_shipping_plugin_calculator_table_rate';
    }
}
