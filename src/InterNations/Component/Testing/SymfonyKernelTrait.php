<?php
namespace InterNations\Component\Testing;

trait SymfonyKernelTrait
{
    public function bootLazyKernel(array $options = [])
    {
        $key = '__SYMFONY_TEST_KERNEL';
        if (!empty($options)) {
            $key .= '_' . hash('sha256', (serialize($options)));
        }

        if (!isset($GLOBALS[$key])) {
            $GLOBALS[$key] = static::createKernel($options);
            $GLOBALS[$key]->boot();
        }

        return $GLOBALS[$key];
    }
}
