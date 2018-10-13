<?php

declare(strict_types=1);

namespace spec\Webgriffe\SyliusTableRateShippingPlugin\EventSubscriber;

use PhpParser\Node\Arg;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ShippingMethod;
use Sylius\Component\Core\Repository\ShippingMethodRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Calculator\TableRateShippingCalculator;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webgriffe\SyliusTableRateShippingPlugin\EventSubscriber\TableRateDeleteSubscriber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TableRateDeleteSubscriberSpec extends ObjectBehavior
{
    function let(ShippingMethodRepositoryInterface $shippingMethodRepository)
    {
        $this->beConstructedWith($shippingMethodRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TableRateDeleteSubscriber::class);
    }

    function it_should_implement_event_subscriber_interface()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function it_should_subscribe_to_table_rate_pre_delete_event()
    {
        self::getSubscribedEvents()->shouldReturn(
            ['webgriffe.shipping_table_rate.pre_delete' => 'onTableRatePreDelete']
        );
    }

    function it_should_stop_event_if_table_rate_is_already_in_use(
        ShippingTableRate $shippingTableRate,
        ResourceControllerEvent $event,
        ShippingMethodRepositoryInterface $shippingMethodRepository
    ) {
        $shippingTableRate->getCode()->willReturn('TABLE_RATE');
        $shippingMethod1 = new ShippingMethod();
        $shippingMethod1->setCode('METHOD1');
        $shippingMethod1->setConfiguration(['WEB-US' => ['table_rate_code' => 'TABLE_RATE']]);
        $shippingMethod2 = new ShippingMethod();
        $shippingMethod2->setCode('METHOD2');
        $shippingMethod2->setConfiguration(['WEB-US' => ['table_rate_code' => 'ANOTHER_RATE']]);
        $shippingMethod3 = new ShippingMethod();
        $shippingMethod3->setCode('METHOD3');
        $shippingMethod3->setConfiguration(
            ['WEB-US' => ['table_rate_code' => 'ANOTHER_RATE'], 'WEB-EU' => ['table_rate_code' => 'TABLE_RATE']]
        );
        $shippingMethodRepository
            ->findBy(['calculator' => TableRateShippingCalculator::TYPE])
            ->willReturn([$shippingMethod1, $shippingMethod2, $shippingMethod3])
        ;
        $event->getSubject()->willReturn($shippingTableRate);
        $event
            ->stop(
                'webgriffe_sylius_table_rate_plugin.ui.shipping_table_rate.already_used_by_shipping_methods',
                ResourceControllerEvent::TYPE_ERROR,
                ['%shipping_methods%' => 'METHOD1, METHOD3'],
                400
            )
            ->shouldBeCalled()
        ;

        $this->onTableRatePreDelete($event);
    }

    function it_should_not_stop_event_if_table_rate_is_not_in_use(
        ShippingTableRate $shippingTableRate,
        ResourceControllerEvent $event,
        ShippingMethodRepositoryInterface $shippingMethodRepository
    ) {
        $event->getSubject()->willReturn($shippingTableRate);
        $shippingMethodRepository
            ->findBy(['calculator' => TableRateShippingCalculator::TYPE])
            ->willReturn([])
        ;
        $event
            ->stop()
            ->shouldNotBeCalled()
        ;

        $this->onTableRatePreDelete($event);
    }
}
