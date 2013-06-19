<?php

namespace PaynetEasy\Paynet\Workflow;

use PaynetEasy\Paynet\Transport\GatewayClient;
use PaynetEasy\Paynet\Query\QueryFactory;
use PaynetEasy\Paynet\Callback\CallbackFactory;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-06-15 at 16:43:36.
 */
class WorkflowFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WorkflowFactory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new WorkflowFactory(new GatewayClient('_'),
                                            new QueryFactory,
                                            new CallbackFactory);
    }

    public function testGetWorkflow()
    {
        $this->assertInstanceOf('PaynetEasy\Paynet\Workflow\MakeRebillWorkflow',
                                $this->object->getWorkflow('make-rebill', array()));

        $this->assertInstanceOf('PaynetEasy\Paynet\Workflow\SaleWorkflow',
                                $this->object->getWorkflow('sale', array()));

        $formWorflow = $this->object->getWorkflow('sale-form', array());

        $this->assertInstanceOf('PaynetEasy\Paynet\Workflow\FormWorkflow', $formWorflow);
        $this->assertEquals('sale-form', $this->readAttribute($formWorflow, 'initialApiMethod'));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Unknown workflow class PaynetEasy\Paynet\Workflow\UnknownWorkflow for workflow with name unknown
     */
    public function testGetWorkflowWithException()
    {
        $this->object->getWorkflow('unknown', array());
    }
}
