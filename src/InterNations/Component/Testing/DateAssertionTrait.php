<?php
namespace InterNations\Component\Testing;

use DateTime;

trait DateAssertionTrait
{
    /**
     * Compares two dates for equality
     *
     * @param DateTime|string $expected
     */
    protected static function assertSameDate($expected, DateTime $actual, bool $notSameInstance = false): void
    {
        if (!$expected instanceof DateTime) {
            $expected = new DateTime($expected, $actual->getTimezone());
        }

        self::assertInstanceOf(get_class($expected), $actual);

        if ($notSameInstance) {
            self::assertNotSame($expected, $actual);
        }

        self::assertSame($expected->format('Y-m-d H:i:s'), $actual->format('Y-m-d H:i:s'));
        self::assertSame($expected->getTimezone()->getName(), $actual->getTimezone()->getName());
    }

    protected function assertDate(DateTime $date): void
    {
        self::assertSame($this->expectedDate->format('Y-m-d H:i:s'), $date->format('Y-m-d H:i:s'));
    }
}
