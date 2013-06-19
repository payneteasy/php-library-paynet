<?php

namespace PaynetEasy\Paynet\Query;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-06-11 at 16:41:48.
 */
class AbstractQueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConcreteQuery
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new ConcreteQuery(array());
    }

    public function testSetApiMethod()
    {
        $this->object->setApiMethod('\PaynetEasy\Paynet\Query\GetCardInfoQuery');
        $this->assertEquals('get-card-info', $this->object->apiMethod);

        $this->object->setApiMethod('\PaynetEasy\Paynet\Query\ReturnQuery');
        $this->assertEquals('get-card-info', $this->object->apiMethod);

        $this->object->apiMethod = null;

        $this->object->setApiMethod('\PaynetEasy\Paynet\Query\ReturnQuery');
        $this->assertEquals('return', $this->object->apiMethod);
    }
}

class ConcreteQuery extends AbstractQuery
{
    public $apiMethod;

    public function __construct(array $config = array())
    {
    }

    public function setApiMethod($class)
    {
        parent::setApiMethod($class);
    }
}