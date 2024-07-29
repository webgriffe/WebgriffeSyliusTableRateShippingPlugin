<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Form\Type\Shipping\Calculator;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;

final class TableRateConfigurationType extends AbstractType implements DataMapperInterface
{
    public function __construct(
        private RepositoryInterface $tableRateRepository,
    ) {
    }

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
                'placeholder' => $messagesNamespace . 'table_rate.placeholder',
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
            ],
        )->setDataMapper($this);
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

    /**
     * @psalm-suppress MoreSpecificImplementedParamType
     * @psalm-suppress PossiblyInvalidArgument
     * @psalm-suppress UnnecessaryVarAnnotation
     */
    public function mapDataToForms(mixed $viewData, \Traversable $forms): void
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!is_array($viewData)) {
            throw new UnexpectedTypeException($viewData, 'array');
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        if (!array_key_exists(self::TABLE_RATE_FIELD_NAME, $viewData)) {
            return;
        }

        // initialize form field values
        $forms['table_rate']->setData($this->tableRateRepository->findOneBy(['code' => $viewData[self::TABLE_RATE_FIELD_NAME]]));
    }

    /**
     * @psalm-suppress MoreSpecificImplementedParamType
     * @psalm-suppress PossiblyInvalidArgument
     * @psalm-suppress UnnecessaryVarAnnotation
     */
    public function mapFormsToData(\Traversable $forms, mixed &$viewData): void
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        // as data is passed by reference, overriding it will change it in
        // the form object as well
        // beware of type inconsistency, see caution below
        $tableRateSelected = $forms[self::TABLE_RATE_FIELD_NAME]->getData();
        if (!$tableRateSelected instanceof ShippingTableRate) {
            $viewData = [];

            return;
        }

        $viewData = [self::TABLE_RATE_FIELD_NAME => $tableRateSelected->getCode()];
    }
}
