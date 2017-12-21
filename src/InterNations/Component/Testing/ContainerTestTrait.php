<?php
namespace InterNations\Component\Testing;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use InvalidArgumentException;
use ReflectionObject;

trait ContainerTestTrait
{
    /**
     * @param mixed[] $config
     * @param Definition[] $definitions
     */
    protected function createContainer(
        ?string $file = null,
        bool $debug = false,
        array $config = [],
        array $definitions = []
    ): ContainerBuilder
    {
        $container = new ContainerBuilder(new ParameterBag(['kernel.debug' => $debug]));
        $container->registerExtension($this->getContainerExtension());

        $this->loadFromFile($container, $file);

        $container->loadFromExtension($this->getContainerConfigPrefix(), $config);
        $container->addDefinitions($definitions);

        foreach ($this->getCompilerPasses() as $compilerPass) {
            $container->addCompilerPass($compilerPass);
        }

        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->compile();

        return $container;
    }

    private function loadFromFile(ContainerBuilder $container, string $file): void
    {
        if ($file === null) {
            return;
        }

        $locator = new FileLocator($this->getContainerFixturePath());

        switch (substr($file, -3)) {
            case 'xml':
                $loader = new XmlFileLoader($container, $locator);
                break;

            case 'yml':
                $loader = new YamlFileLoader($container, $locator);
                break;

            case 'xml':
                $loader = new PhpFileLoader($container, $locator);
                break;

            default:
                throw new InvalidArgumentException('Invalid file type');
                break;
        }

        $loader->load($file);
    }

    /** @return CompilerPassInterface[] */
    protected function getCompilerPasses(): array
    {
        return [];
    }

    protected function getContainerFixturePath(): string
    {
        static $fixturePath;

        if ($fixturePath === null) {
            $class = new ReflectionObject($this);
            $fixturePath = dirname($class->getFileName()) . '/../Fixtures';
        }

        return $fixturePath;
    }

    protected function getContainerConfigPrefix(): string
    {
        return 'inter_nations_' . strtolower($this->getBundle()[1]);
    }

    protected function getContainerExtension(): ExtensionInterface
    {
        [$namespace, $name] = $this->getBundle();
        $className = $namespace . '\\DependencyInjection\\InterNations' . $name . 'Extension';

        return new $className;
    }

    /** @return string[] */
    protected function getBundle(): array
    {
        static $bundle;

        if ($bundle === null) {
            preg_match('/(?P<ns>.*\\\\(?P<name>.+)Bundle)\\\\.*/', get_class($this), $matches);
            $bundle = [$matches['ns'], $matches['name']];
        }

        return $bundle;
    }
}
