<?php
namespace InterNations\Component\Testing;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use InvalidArgumentException;
use ReflectionObject;

trait ContainerTestTrait
{
    protected function createContainer($file = null, $debug = false, array $config = [], array $definitions = [])
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

    private function loadFromFile(ContainerBuilder $container, $file)
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

    protected function getCompilerPasses()
    {
        return [];
    }

    protected function getContainerFixturePath()
    {
        static $fixturePath;

        if ($fixturePath === null) {
            $class = new ReflectionObject($this);
            $fixturePath = dirname($class->getFileName()) . '/../Fixtures';
        }

        return $fixturePath;
    }

    protected function getContainerConfigPrefix()
    {
        return 'inter_nations_' . strtolower($this->getBundle()[1]);
    }

    protected function getContainerExtension()
    {
        list($namespace, $name) = $this->getBundle();
        $className = $namespace . '\\DependencyInjection\\InterNations' . $name . 'Extension';

        return new $className;
    }

    protected function getBundle()
    {
        static $bundle;

        if ($bundle === null) {
            preg_match('/(?P<ns>.*\\\\(?P<name>.+)Bundle)\\\\.*/', get_class($this), $matches);
            $bundle = [$matches['ns'], $matches['name']];
        }

        return $bundle;
    }
}
