<?php
namespace InterNations\Component\Testing;

trait SymfonyKernelTrait
{
    /** @var string */
    private $kernelKey;

    public function bootLazyKernel(array $options = [])
    {
        $this->kernelKey = '__SYMFONY_TEST_KERNEL';

        if (!empty($options)) {
            $this->kernelKey .= '_' . hash('sha256', (serialize($options)));
        }

        if (!isset($GLOBALS[$this->kernelKey])) {
            $GLOBALS[$this->kernelKey] = static::createKernel($options);
            $GLOBALS[$this->kernelKey]->boot();
        }

        return $GLOBALS[$this->kernelKey];
    }

    public function shutdownLazyKernel()
    {
        $GLOBALS[$this->kernelKey]->shutdown();
        unset($GLOBALS[$this->kernelKey]);
    }
}
