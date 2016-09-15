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
        if (method_exists($this, 'createMock')) {
            trigger_error('Directly use createMock() instead', E_USER_DEPRECATED);

            return $this->createMock($className);
        }

        return $this->getMock($className, $methods, [], '', false, false, true, false);
    }
}
