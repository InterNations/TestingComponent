<?php
namespace InterNations\Component\Testing;

use Symfony\Component\HttpKernel\KernelInterface;

trait SymfonyKernelTrait
{
    /** @var string */
    private $kernelKey;

    /** @param mixed[] $options */
    public function bootLazyKernel(array $options = []): KernelInterface
    {
        $this->kernelKey = '__SYMFONY_TEST_KERNEL__' . static::$class;

        if (!empty($options)) {
            $this->kernelKey .= '_' . hash('sha256', serialize($options));
        }

        if (!isset($GLOBALS[$this->kernelKey])) {
            $GLOBALS[$this->kernelKey] = static::createKernel($options);
            $GLOBALS[$this->kernelKey]->boot();
        }

        return $GLOBALS[$this->kernelKey];
    }

    public function shutdownLazyKernel(): void
    {
        $GLOBALS[$this->kernelKey]->shutdown();
        unset($GLOBALS[$this->kernelKey]);
    }
}
