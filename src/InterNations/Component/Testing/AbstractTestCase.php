<?php
namespace InterNations\Component\Testing;

use PHPUnit_Framework_TestCase as BaseTestCase;

abstract class AbstractTestCase extends BaseTestCase
{
    use MockTrait;
}
