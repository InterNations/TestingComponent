<?php
namespace InterNations\Component\Testing;

use ReflectionClass;

trait AccessTrait
{
    /**
     * @param object|string $object
     * @return mixed
     */
    protected static function getNonPublicProperty($object, string $propertyName, ?string $reflectionClass = null)
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
     * @param mixed $value
     */
    protected static function setNonPublicProperty(
        $object,
        string $propertyName,
        $value,
        ?string $reflectionClass = null
    ): void
    {
        $reflected = new ReflectionClass($reflectionClass ?: $object);
        $property = $reflected->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    /**
     * Call private and protected methods on an object
     *
     * @param mixed[] $args
     * @return mixed
     */
    protected static function callNonPublicMethod(object $object, string $methodName, array $args = [])
    {
        $class = new ReflectionClass($object);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }
}
