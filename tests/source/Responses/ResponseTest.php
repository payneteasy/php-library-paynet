<?php

namespace PaynetEasy\Paynet\Responses;

use \ReflectionMethod;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-06-06 at 15:21:28.
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Response
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Response;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers PaynetEasy\Paynet\Responses\Response::isError
     */
    public function testIsError()
    {
        $this->object->exchangeArray(array('type' => 'validation-error'));
        $this->assertTrue($this->object->isError());

        $this->object->exchangeArray(array('status' => 'error'));
        $this->assertTrue($this->object->isError());

        $this->object->exchangeArray(array('type' => 'async-response', 'status' => 'approved'));
        $this->assertFalse($this->object->isError());
    }

    /**
     * @covers PaynetEasy\Paynet\Responses\Response::isProcessing
     */
    public function testIsProcessing()
    {
        $this->object->exchangeArray(array('status' => 'processing'));
        $this->assertTrue($this->object->isProcessing());

        $this->object->exchangeArray(array('type' => 'async-response'));
        $this->assertTrue($this->object->isProcessing());

        $this->object->exchangeArray(array('status' => 'error'));
        $this->assertFalse($this->object->isProcessing());

        $this->object->exchangeArray(array('type' => 'error'));
        $this->assertFalse($this->object->isProcessing());
    }

    /**
     * @covers PaynetEasy\Paynet\Responses\Response::isDeclined
     */
    public function testIsDeclined()
    {
        $this->assertFalse($this->object->isDeclined());

        $this->object->exchangeArray(array('status' => 'filtered'));
        $this->assertTrue($this->object->isDeclined());
    }

    /**
     * @covers PaynetEasy\Paynet\Responses\Response::redirect
     * @todo   Implement testRedirect().
     */
    public function testRedirect()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
        'This test has not been implemented yet.'
        );
    }

    /**
     * @covers PaynetEasy\Paynet\Responses\Response::getAnyKey
     */
    public function testGetAnyKey()
    {
        $method = new ReflectionMethod('PaynetEasy\Paynet\Responses\Response', 'getAnyKey');
        $method->setAccessible(true);

        $this->object->exchangeArray(array('first-key' => null, 'first_key' => 2));

        $this->assertEquals(2, $method->invoke($this->object, array('first-key', 'first_key')));
        $this->assertEquals(2, $method->invoke($this->object, array('first_key', 'firstkey')));
        $this->assertNull($method->invoke($this->object, array('first', 'firstkey')));
    }

    /**
     * @covers PaynetEasy\Paynet\Responses\Response::getValue
     */
    public function testGetValue()
    {
        $method = new ReflectionMethod('PaynetEasy\Paynet\Responses\Response', 'getValue');
        $method->setAccessible(true);

        $this->object->exchangeArray(array('first-key' => 1, 'first_key' => 2));

        $this->assertEquals(2, $method->invoke($this->object, 'first_key'));
        $this->assertNull($method->invoke($this->object, 'first'));
    }
}
