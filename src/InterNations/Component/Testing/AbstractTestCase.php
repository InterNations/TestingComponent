<?php
namespace InterNations\Component\Testing;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    use MockTrait;
}
