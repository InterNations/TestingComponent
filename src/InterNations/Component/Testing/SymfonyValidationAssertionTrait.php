<?php
namespace InterNations\Component\Testing;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Mapping\ClassMetadataInterface;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;

trait SymfonyValidationAssertionTrait
{
    /** @var ClassMetadataInterface[] */
    private static $metadataMap = [];

    /**
     * @param string $className
     * @param string $propertyName
     * @return mixed
     */
    private function getPropertyConstraints($className, $propertyName)
    {
        if (!isset(self::$metadataMap[$className])) {
            $factory = new LazyLoadingMetadataFactory(new AnnotationLoader(new AnnotationReader()));
            self::$metadataMap[$className] = $factory->getMetadataFor($className);
        }

        $metadata = self::$metadataMap[$className];

        $this->assertTrue(
            $metadata->hasPropertyMetadata($propertyName),
            sprintf('No assertions defined for property "%s" in class "%s"', $propertyName, $className)
        );

        $propertyMetadatas = $metadata->getPropertyMetadata($propertyName);
        $this->assertCount(1, $propertyMetadatas);

        return current($propertyMetadatas);
    }

    /**
     * @param string $constraintClassName
     * @return string
     */
    private function getConstraintClassName($constraintClassName)
    {
        return strpos($constraintClassName, '\\') === false
            ? 'Symfony\\Component\\Validator\\Constraints\\' . $constraintClassName
            : $constraintClassName;
    }

    /**
     * @param string $className
     * @param string $propertyName
     * @param boolean $allowCascadeConstraintOnly
     */
    protected function assertValidationCascades($className, $propertyName, $allowCascadeConstraintOnly = true)
    {
        $propertyMetadata = $this->getPropertyConstraints($className, $propertyName);
        $this->assertTrue($propertyMetadata->isCascaded());

        if ($allowCascadeConstraintOnly) {
            $this->assertCount(
                0,
                $propertyMetadata->constraints,
                'No constraint definitions allowed for cascaded property.'
            );
        }
    }

    /**
     * Asserts the count of symfony validators
     *
     * @param string $className
     * @param string $propertyName
     * @param integer $count
     */
    protected function assertConstraintCount($className, $propertyName, $count)
    {
        $propertyMetadata = $this->getPropertyConstraints($className, $propertyName);

        $this->assertCount($count, $propertyMetadata->constraints);
    }

    /**
     * Asserts a certain symfony validation constraint is not defined for property
     *
     * @param string $className
     * @param string $propertyName
     * @param string $constraintClassName
     */
    protected function assertConstraintNotForProperty($className, $propertyName, $constraintClassName)
    {
        $propertyMetadata = $this->getPropertyConstraints($className, $propertyName);
        $constraintClassName = $this->getConstraintClassName($constraintClassName);

        $executed = false;

        foreach ($propertyMetadata->constraints as $constraint) {
            $this->assertNotSame(
                get_class($constraint),
                $constraintClassName,
                sprintf(
                    'Constraint "%s" should not be defined for "%s:$%s"',
                    $constraintClassName,
                    $className,
                    $propertyName
                )
            );

            $executed = true;
        }

        $this->assertTrue($executed, 'Internal error. Loop not executed');
    }

    /**
     * Asserts symfony validation rules
     *
     * @param string $className
     * @param string $propertyName
     * @param string $constraintClassName
     * @param array $properties
     * @param array|string $expectedValidationGroups
     */
    protected function assertConstraintForProperty(
        $className,
        $propertyName,
        $constraintClassName,
        array $properties = [],
        $expectedValidationGroups = null,
        $propertyNumber = 1
    )
    {
        $propertyMetadata = $this->getPropertyConstraints($className, $propertyName);
        $constraintClassName = $this->getConstraintClassName($constraintClassName);

        $expectedValidationGroups = $expectedValidationGroups ? (array) $expectedValidationGroups : ['Default'];

        if (in_array('Default', $expectedValidationGroups, true)) {
            $shortClassName = substr($className, strrpos($className, '\\') + 1);
            $expectedValidationGroups[] = $shortClassName;
        }

        $matched = false;
        $currentPropertyNumber = 1;

        foreach ($propertyMetadata->constraints as $constraint) {
            if (get_class($constraint) === $constraintClassName) {
                if ($propertyNumber > $currentPropertyNumber) {
                    $currentPropertyNumber++;
                    continue;
                }

                foreach ($properties as $constraintProperty => $value) {
                    $this->assertObjectHasAttribute(
                        $constraintProperty,
                        $constraint,
                        sprintf(
                            'Parameter "%s" expected but not defined in constraint "%s" for "%s::$%s"',
                            $constraintProperty,
                            $constraintClassName,
                            $className,
                            $propertyName
                        )
                    );
                    $this->assertSame(
                        $value,
                        $constraint->{$constraintProperty},
                        sprintf(
                            'Property "%s" did not match expected value "%s" defined in constraint "%s" for "%s::$%s"',
                            $constraintProperty,
                            var_export($value, true),
                            $constraintClassName,
                            $className,
                            $propertyName
                        )
                    );
                }

                $this->assertSame(
                    count($constraint->groups),
                    count($expectedValidationGroups),
                    sprintf(
                        'Number of expected and existing validation groups did not match. Expected %s, got %s',
                        var_export((array) $expectedValidationGroups, true),
                        var_export($constraint->groups, true)
                    )
                );

                $groupError = 'Constraint "%s" for property "%s" is expected to be bound to group "%s", but is '
                            . 'limited to %s';

                foreach ((array) $expectedValidationGroups as $expectedValidationGroup) {
                    $this->assertContains(
                        $expectedValidationGroup,
                        $constraint->groups,
                        sprintf(
                            $groupError,
                            $constraintClassName,
                            $propertyName,
                            $expectedValidationGroup,
                            var_export($constraint->groups, true)
                        )
                    );
                }

                $matched = true;
                break;
            }
        }

        $this->assertTrue($matched, 'Constraint ' . $constraintClassName . ' not defined for ' . $className);
    }
}
