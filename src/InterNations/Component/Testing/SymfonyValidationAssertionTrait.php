<?php
namespace InterNations\Component\Testing;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Mapping\ClassMetadataInterface;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Validator\Mapping\PropertyMetadataInterface;

trait SymfonyValidationAssertionTrait
{
    /** @var ClassMetadataInterface[] */
    private static $metadataMap = [];

    private static function getPropertyConstraints(string $className, string $propertyName): ?PropertyMetadataInterface
    {
        if (!isset(self::$metadataMap[$className])) {
            $factory = new LazyLoadingMetadataFactory(new AnnotationLoader(new AnnotationReader()));
            self::$metadataMap[$className] = $factory->getMetadataFor($className);
        }

        $metadata = self::$metadataMap[$className];

        self::assertTrue(
            $metadata->hasPropertyMetadata($propertyName),
            sprintf('No assertions defined for property "%s" in class "%s"', $propertyName, $className)
        );

        $propertyMetadatas = $metadata->getPropertyMetadata($propertyName);
        self::assertCount(1, $propertyMetadatas);

        return current($propertyMetadatas);
    }

    private static function getConstraintClassName(string $constraintClassName): string
    {
        return strpos($constraintClassName, '\\') === false
            ? 'Symfony\\Component\\Validator\\Constraints\\' . $constraintClassName
            : $constraintClassName;
    }

    protected static function assertValidationCascades(
        string $className,
        string $propertyName,
        bool $allowCascadeConstraintOnly = true
    ): void
    {
        $propertyMetadata = self::getPropertyConstraints($className, $propertyName);
        self::assertTrue($propertyMetadata->isCascaded());

        if ($allowCascadeConstraintOnly) {
            self::assertCount(
                0,
                $propertyMetadata->constraints,
                'No constraint definitions allowed for cascaded property.'
            );
        }
    }

    protected static function assertConstraintCount(string $className, string $propertyName, int $count): void
    {
        $propertyMetadata = self::getPropertyConstraints($className, $propertyName);

        self::assertCount($count, $propertyMetadata->constraints);
    }

    /**
     * Asserts a certain symfony validation constraint is not defined for property
     */
    protected static function assertConstraintNotForProperty(
        string $className,
        string $propertyName,
        string $constraintClassName
    ): void
    {
        $propertyMetadata = self::getPropertyConstraints($className, $propertyName);
        $constraintClassName = self::getConstraintClassName($constraintClassName);

        $executed = false;

        foreach ($propertyMetadata->constraints as $constraint) {
            self::assertNotSame(
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

        self::assertTrue($executed, 'Internal error. Loop not executed');
    }

    /**
     * Asserts symfony validation rules
     *
     * @param string[] $expectedPropertiesMap Key/value map of expected properties
     * @param array|string $expectedValidationGroups
     */
    protected static function assertConstraintForProperty(
        string $className,
        string $propertyName,
        string $constraintClassName,
        array $expectedPropertiesMap = [],
        $expectedValidationGroups = null,
        int $propertyIndex = 1
    ): void
    {
        $propertyMetadata = self::getPropertyConstraints($className, $propertyName);
        $constraintClassName = self::getConstraintClassName($constraintClassName);

        $expectedValidationGroups = $expectedValidationGroups ? (array) $expectedValidationGroups : ['Default'];

        if (in_array('Default', $expectedValidationGroups, true)) {
            $shortClassName = substr($className, strrpos($className, '\\') + 1);
            $expectedValidationGroups[] = $shortClassName;
        }

        $matched = false;
        $currentPropertyIndex = 1;

        foreach ($propertyMetadata->constraints as $constraint) {
            if (get_class($constraint) === $constraintClassName) {
                if ($propertyIndex > $currentPropertyIndex) {
                    $currentPropertyIndex++;
                    continue;
                }

                foreach ($expectedPropertiesMap as $constraintProperty => $value) {
                    self::assertObjectHasAttribute(
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
                    self::assertSame(
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

                $expectedValidationGroups = (array) $expectedValidationGroups;
                self::assertSame(
                    count($constraint->groups),
                    count($expectedValidationGroups),
                    sprintf(
                        'Number of expected and existing validation groups did not match. Expected %s, got %s',
                        var_export($expectedValidationGroups, true),
                        var_export($constraint->groups, true)
                    )
                );

                $groupError = 'Constraint "%s" for property "%s" is expected to be bound to group "%s", but is '
                            . 'limited to %s';

                foreach ($expectedValidationGroups as $expectedValidationGroup) {
                    self::assertContains(
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

        self::assertTrue($matched, 'Constraint ' . $constraintClassName . ' not defined for ' . $className);
    }
}
