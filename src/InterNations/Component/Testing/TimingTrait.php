<?php
namespace InterNations\Component\Testing;

trait TimingTrait
{
    protected function assertTiming(float $maxDurationInMs, callable $callable, int $iterations = 20): void
    {
        $duration = 0;

        for ($a = 0; $a < $iterations; ++$a) {
            $start = microtime(true);
            $callable();
            $end = microtime(true);
            $duration += ($end - $start);
        }

        $this->assertLessThanOrEqual($maxDurationInMs, ($duration / $iterations) * 1000);
    }
}
