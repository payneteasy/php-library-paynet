<?php

namespace PaynetEasy\Paynet\Callback;

use PaynetEasy\Paynet\Transport\CallbackResponse;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-06-19 at 14:39:15.
 */
class CallbackFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CallbackFactory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new CallbackFactory;
    }

    public function testGetCallback()
    {
        $config     = array('control' => '_');

        $this->assertInstanceOf('PaynetEasy\Paynet\Callback\RedirectUrlCallback',
                                $this->object->getCallback(new CallbackResponse(), $config));

        $this->assertInstanceOf('PaynetEasy\Paynet\Callback\FakeCallback',
                                $this->object->getCallback(new CallbackResponse(array('type' => 'fake')), $config));

        $saleCallback = $this->object->getCallback(new CallbackResponse(array('type' => 'sale')), $config);

        $this->assertInstanceOf('PaynetEasy\Paynet\Callback\ServerCallbackUrlCallback', $saleCallback);
        $this->assertEquals('sale', $this->readAttribute($saleCallback, 'callbackType'));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Unknown callback class 'PaynetEasy\Paynet\Callback\UnknownCallback' for callback with type 'unknown'
     */
    public function testGetQueryWithException()
    {
        $this->object->getCallback(new CallbackResponse(array('type' => 'unknown')), array());
    }
}
