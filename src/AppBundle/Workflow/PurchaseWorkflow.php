<?php

namespace AppBundle\Workflow;

use Symfony\Component\Workflow\Definition;

class PurchaseWorkflow
{
    private $definition;

    public function setUp()
    {
        $this->definition = new Definition();
        $this->definition->addPlaces([
            'in_stock',
            'in_cart',
            'in_wish_list',
            'ready_to_purchase',
            'paid'
        ]);

        $this->definition->addTransition([
            'put_in_cart',   // name
            ['in_stock'],    // input places
            ['in_cart'],     // output places
        ]);

        $this->definition->addTransition([
            'check_out'
            ['in_cart'],
            ['in_wish_list', 'ready_to_purchase'],
        ]);

        $this->definition->addTransition([
            'send_to_friend',
            ['in_wish_list'],
            ['ready_to_purchase'],
        ]);

        $this->definition->addTransition([
            'pay',
            ['ready_to_purchase'],
            ['paid'],
        ]);
    }
}
