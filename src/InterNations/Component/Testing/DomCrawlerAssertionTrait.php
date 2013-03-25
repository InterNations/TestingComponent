<?php
namespace InterNations\Component\Testing;

use Symfony\Component\DomCrawler\Crawler;
use InvalidArgumentException;

trait DomCrawlerAssertionTrait
{
    /**
     * @param string $expected
     * @param string $document
     * @param string $selector
     * @param string $message
     */
    public function assertHtmlSelector($expected, $document, $selector, $message = '')
    {
        $this->assertSame($expected, (new Crawler($document))->filter($selector)->text(), $message);
    }

    /**
     * @param string $expected
     * @param string $document
     * @param string $selector
     * @param string $message
     */
    public function assertNotHtmlSelector($expected, $document, $selector, $message = '')
    {
        try {
            $value = (new Crawler($document))->filter($selector)->text();
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        $this->assertNotSame($expected, $value, $message);
    }

    /**
     * @param string $expected
     * @param string $document
     * @param string $selector
     * @param string $message
     */
    public function assertHtmlSelectorContains($expected, $document, $selector, $message = '')
    {
        $this->assertContains($expected, (new Crawler($document))->filter($selector)->text(), $message);
    }

    /**
     * @param string $expected
     * @param string $document
     * @param string $selector
     * @param string $message
     */
    public function assertNotHtmlSelectorContains($expected, $document, $selector, $message = '')
    {
        try {
            $value = (new Crawler($document))->filter($selector)->text();
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        $this->assertNotContains($expected, $value, $message);
    }

    /**
     * @param string $expected
     * @param string $document
     * @param string $selector
     * @param string $attribute
     * @param string $message
     */
    public function assertHtmlSelectorAttribute($expected, $document, $selector, $attribute, $message = '')
    {
        $this->assertSame($expected, (new Crawler($document))->filter($selector)->attr($attribute), $message);
    }

    /**
     * @param string $expected
     * @param string $document
     * @param string $selector
     * @param string $attribute
     * @param string $message
     */
    public function assertNotHtmlSelectorAttribute($expected, $document, $selector, $attribute, $message = '')
    {
        try {
            $value = (new Crawler($document))->filter($selector)->attr($attribute);
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        $this->assertNotSame($expected, $value, $message);
    }

    /**
     * @param string $expected
     * @param string $document
     * @param string $xpath
     * @param string $message
     */
    public function assertXpathSelector($expected, $document, $xpath, $message = '')
    {
        $this->assertSame($expected, (new Crawler($document))->filterXPath($xpath)->text(), $message);
    }

    /**
     * @param string $expected
     * @param string $document
     * @param string $xpath
     * @param string $message
     */
    public function assertNotXpathSelector($expected, $document, $xpath, $message = '')
    {
        try {
            $value = (new Crawler($document))->filterXPath($xpath)->text();
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        $this->assertNotSame($expected, $value, $message);
    }

    /**
     * @param string $expected
     * @param string $document
     * @param string $xpath
     * @param string $message
     */
    public function assertXpathSelectorContains($expected, $document, $xpath, $message = '')
    {
        $this->assertContains($expected, (new Crawler($document))->filterXPath($xpath)->text(), $message);
    }

    /**
     * @param string $expected
     * @param string $document
     * @param string $xpath
     * @param string $message
     */
    public function assertNotXpathSelectorContains($expected, $document, $xpath, $message = '')
    {
        try {
            $value = (new Crawler($document))->filterXPath($xpath)->text();
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        $this->assertNotContains($expected, $value, $message);
    }

    /**
     * @param string $expected
     * @param string $document
     * @param string $xpath
     * @param string $attribute
     * @param string $message
     */
    public function assertXpathSelectorAttribute($expected, $document, $xpath, $attribute, $message = '')
    {
        $this->assertSame($expected, (new Crawler($document))->filterXPath($xpath)->attr($attribute), $message);
    }

    /**
     * @param string $expected
     * @param string $document
     * @param string $xpath
     * @param string $attribute
     * @param string $message
     */
    public function assertNotXpathSelectorAttribute($expected, $document, $xpath, $attribute, $message = '')
    {
        try {
            $value = (new Crawler($document))->filterXPath($xpath)->attr($attribute);
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        $this->assertNotSame($expected, $value, $message);
    }
}
