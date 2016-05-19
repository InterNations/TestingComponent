<?php
namespace InterNations\Component\Testing;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

abstract class AbstractCommandTestCase extends AbstractTestCase
{
    /**
     * @var CommandTester
     */
    protected $commandTester;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var Application
     */
    protected $application;

    public function setUp()
    {
        parent::setUp();

        $this->container = $this->getSimpleMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->kernel = $this->getSimpleMock('Symfony\Component\HttpKernel\KernelInterface');
        $this->kernel
            ->expects($this->any())
            ->method('getContainer')
            ->will($this->returnValue($this->container));

        $this->application = new Application($this->kernel);
    }
}
