<?php
declare(strict_types=1);

namespace spec\Webgriffe\SyliusTableRateShippingPlugin\Form\EventSubscriber;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;

class AddCurrencySubscriberSpec extends ObjectBehavior
{
    function it_implements_event_subscriber_interface(): void
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function it_subscribes_to_event(): void
    {
        $this::getSubscribedEvents()->shouldReturn([FormEvents::PRE_SET_DATA => 'preSetData']);
    }

    function it_sets_currency_as_disabled_when_table_rate_is_not_new(
        FormEvent $event,
        ShippingTableRate $shippingTableRate,
        FormInterface $form
    ): void {
        $event->getData()->willReturn($shippingTableRate);
        $event->getForm()->willReturn($form);

        $shippingTableRate->getId()->willReturn(2);

        $form
            ->add('currency', Argument::type('string'), Argument::withEntry('disabled', true))
            ->shouldBeCalled()
        ;

        $this->preSetData($event);
    }

    function it_does_not_set_currency_as_disabled_when_table_rate_is_new(
        FormEvent $event,
        ShippingTableRate $shippingTableRate,
        FormInterface $form
    ): void {
        $event->getData()->willReturn($shippingTableRate);
        $event->getForm()->willReturn($form);

        $shippingTableRate->getId()->willReturn(null);

        $form
            ->add('currency', Argument::type('string'), Argument::withEntry('disabled', false))
            ->shouldBeCalled()
        ;

        $this->preSetData($event);
    }
}
