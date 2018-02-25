<?php
namespace InterNations\Component\Testing;

trait TimingTrait
{
    protected static function assertTiming(float $maxDurationInMs, callable $callable, int $iterations = 20): void
    {
        $duration = 0;

        for ($a = 0; $a < $iterations; ++$a) {
            $start = microtime(true);
            $callable();
            $end = microtime(true);
            $duration += ($end - $start);
        }

        self::assertLessThanOrEqual($maxDurationInMs, ($duration / $iterations) * 1000);
    }
}
