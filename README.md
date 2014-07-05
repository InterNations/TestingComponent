# Test helpers for Symfony projects
[![Build Status](https://travis-ci.org/InterNations/TestingComponent.svg)](https://travis-ci.org/InterNations/TestingComponent) [![Dependency Status](https://www.versioneye.com/user/projects/5347af6afe0d070896000135/badge.png)](https://www.versioneye.com/user/projects/5347af6afe0d070896000135)

A collection of test helpers to ease testing of Symfony2 projects.


### The base test class

```php
<?php
use InterNations\Component\Testing\AbstractTestCase;

class MyTest extends AbstractTestCase
{
    private $sut;

    public function setUp()
    {
        $this->sut = $this->getSimpleMock('MyComponent');
    }
}
```

### Accessing restricted members
```php
<?php
use InterNations\Component\Testing\AccessTrait;


class MyTest ...
{
    use AccessTrait;

    public function testSomething()
    {
        $this->setNonPublicProperty($this->sut, 'privateProperty', 'value');
        $this->callNonPublicMethod($this->sut, 'protectedMethod', ['arg1', 'arg2']);
    }
}

```
