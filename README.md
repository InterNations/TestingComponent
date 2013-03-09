# Test helpers for Symfony projects

A collection of test helpers to ease testing of Symfony2 projects.


### Example usage

```
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
