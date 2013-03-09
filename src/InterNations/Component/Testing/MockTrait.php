<?php
namespace InterNations\Component\Testing;

use PHPUnit_Framework_MockObject_MockObject as MockObject;

trait MockTrait
{
    /**
     * @param string $className
     * @param array $methods
     * @return MockObject
     */
    protected function getSimpleMock($className, array $methods = [])
    {
        return $this->getMock($className, $methods, [], '', false, false, true, false);
    }
}
