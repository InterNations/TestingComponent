<?php
namespace InterNations\Component\Testing\Tests;

use InterNations\Component\Testing\AbstractTestCase;
use InterNations\Component\Testing\DomCrawlerAssertionTrait;

class DomCrawlerAssertionTraitTest extends AbstractTestCase
{
    use DomCrawlerAssertionTrait;

    private $fixture;

    public function setUp()
    {
        $this->fixture = file_get_contents(__DIR__ . '/Fixtures/DomCrawlerFixture.html');
    }

    public function testHtmlSelector()
    {
        $this->assertHtmlSelector('Some link', $this->fixture, 'a');
        $this->assertHtmlSelector('Some link', $this->fixture, '.container a');
        $this->assertHtmlSelector('Some link', $this->fixture, 'div a');
    }

    public function testNotHtmlSelector()
    {
        $this->assertNotHtmlSelector('SoMe LiNk', $this->fixture, '.container a');
        $this->assertNotHtmlSelector('Other link', $this->fixture, '.container a');
    }

    public function testHtmlSelectorContains()
    {
        $this->assertHtmlSelectorContains('link', $this->fixture, '.container a');
        $this->assertHtmlSelectorContains('Some', $this->fixture, '.container a');
    }

    public function testNotHtmlSelectorContains()
    {
        $this->assertNotHtmlSelectorContains('LiNk', $this->fixture, '.container a');
        $this->assertNotHtmlSelectorContains('some', $this->fixture, '.container a');
        $this->assertNotHtmlSelectorContains('invalid', $this->fixture, '.container a');
    }

    public function testHtmlSelectorAttribute()
    {
        $this->assertHtmlSelectorAttribute('/some/resource', $this->fixture, '.container a', 'href');
    }

    public function testNotHtmlSelectorAttribute()
    {
        $this->assertNotHtmlSelectorAttribute('', $this->fixture, '.container a', 'href');
        $this->assertNotHtmlSelectorAttribute('/some', $this->fixture, '.container a', 'href');
        $this->assertNotHtmlSelectorAttribute('/resource', $this->fixture, '.container a', 'href');
        $this->assertNotHtmlSelectorAttribute('/some/resource/', $this->fixture, '.container a', 'href');
    }

    public function testXpathSelector()
    {
        $this->assertXpathSelector('Some link', $this->fixture, '//a');
        $this->assertXpathSelector('Some link', $this->fixture, '//div/a');
        $this->assertXpathSelector('Some link', $this->fixture, '//*[@class="container"]/a');
    }

    public function testNotXpathSelector()
    {
        $this->assertNotXpathSelector('SoMe LiNk', $this->fixture, '//*[@class="container"]/a');
        $this->assertNotXpathSelector('Other link', $this->fixture, '//*[@class="container"]/a');
    }

    public function testXpathSelectorContains()
    {
        $this->assertXpathSelectorContains('link', $this->fixture, '//*[@class="container"]/a');
        $this->assertXpathSelectorContains('Some', $this->fixture, '//*[@class="container"]/a');
    }

    public function testNotXpathSelectorContains()
    {
        $this->assertNotXpathSelectorContains('LiNk', $this->fixture, '//*[@class="container"]/a');
        $this->assertNotXpathSelectorContains('some', $this->fixture, '//*[@class="container"]/a');
        $this->assertNotXpathSelectorContains('invalid', $this->fixture, '//*[@class="container"]/a');
    }

    public function testXpathSelectorAttribute()
    {
        $this->assertXpathSelectorAttribute('/some/resource', $this->fixture, '//*[@class="container"]/a', 'href');
    }

    public function testNotXpathSelectorAttribute()
    {
        $this->assertNotXpathSelectorAttribute('', $this->fixture, '//*[@class="container"]/a', 'href');
        $this->assertNotXpathSelectorAttribute('/some', $this->fixture, '//*[@class="container"]/a', 'href');
        $this->assertNotXpathSelectorAttribute('/resource', $this->fixture, '//*[@class="container"]/a', 'href');
        $this->assertNotXpathSelectorAttribute('/some/resource/', $this->fixture, '//*[@class="container"]/a', 'href');
    }
}

