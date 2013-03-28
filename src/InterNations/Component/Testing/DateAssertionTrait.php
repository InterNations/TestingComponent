<?php
namespace InterNations\Component\Testing;

use DateTime;

trait DateAssertionTrait
{
    /**
     * Compares two dates for equality
     *
     * @param DateTime|string $expected
     * @param DateTime $actual
     * @param bool $notSame
     */
    public function assertSameDate($expected, DateTime $actual, $notSame = false)
    {
        if (!$expected instanceof DateTime) {
            $expected = new DateTime($expected, $actual->getTimezone());
        }

        $this->assertInstanceOf(get_class($expected), $actual);
        if ($notSame) {
            $this->assertNotSame($expected, $actual);
        }
        $this->assertSame($expected->format('Y-m-d H:i:s'), $actual->format('Y-m-d H:i:s'));
        $this->assertSame($expected->getTimezone()->getName(), $actual->getTimezone()->getName());
    }

    /**
     * @param DateTime $date
     */
    public function assertDate(DateTime $date)
    {
        $this->assertSame($this->expectedDate->format('Y-m-d H:i:s'), $date->format('Y-m-d H:i:s'));
    }
}
