<?php
namespace InterNations\Component\Testing;

use ReflectionClass;

trait AccessTrait
{
    /**
     * @param object|string $object
     * @param string g$propertyName
     * @param string|null $reflectionClass
     * @return mixed
     */
    public function getNonPublicProperty($object, $propertyName, $reflectionClass = null)
    {
        $reflectionClass = $reflectionClass ?: $object;
        $reflected = new ReflectionClass($reflectionClass);
        $property = $reflected->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    /**
     * Set a non-public member of an object or class
     *
     * @param object|string $object
     * @param string $propertyName
     * @param mixed $value
     * @param string|null $reflectionClass
     */
    protected function setNonPublicProperty($object, $propertyName, $value, $reflectionClass = null)
    {
        $reflectionClass = $reflectionClass ?: $object;
        $reflected = new ReflectionClass($reflectionClass);
        $property = $reflected->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    /**
     * Call private and protected methods on an object
     *
     * @param object $object
     * @param string $methodName
     * @param array  $args
     * @return mixed
     */
    protected function callNonPublicMethod($object, $methodName, array $args = [])
    {
        $class = new ReflectionClass($object);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }
}
