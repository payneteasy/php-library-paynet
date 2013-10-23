<?php

namespace PaynetEasy\PaynetEasyApi\Util;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-10-23 at 19:22:40.
 */
class RegionFinderTest extends \PHPUnit_Framework_TestCase
{
    public function testHasCountryByCode()
    {
        $this->assertTrue(RegionFinder::hasCountryByCode('US'));
        $this->assertFalse(RegionFinder::hasCountryByCode('USA'));
    }

    public function testHasStateByCode()
    {
        $this->assertTrue(RegionFinder::hasStateByCode('NY'));
        $this->assertFalse(RegionFinder::hasStateByCode('OO'));
    }

    public function testHasStateByName()
    {
        $this->assertTrue(RegionFinder::hasStateByName('New York'));
        $this->assertFalse(RegionFinder::hasStateByName('New Jork'));
    }

    public function testGetStateCode()
    {
        $this->assertEquals('NY', RegionFinder::getStateCode('New York'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Unknown state name 'New Jork'
     */
    public function testGetStateCodeWithUnknownStateName()
    {
        RegionFinder::getStateCode('New Jork');
    }
}
