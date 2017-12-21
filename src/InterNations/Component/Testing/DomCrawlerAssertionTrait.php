<?php
namespace InterNations\Component\Testing;

use Symfony\Component\DomCrawler\Crawler;
use InvalidArgumentException;

trait DomCrawlerAssertionTrait
{
    public function assertHtmlSelector(string $expected, string $document, string $selector, string $message = ''): void
    {
        $this->assertSame($expected, (new Crawler($document))->filter($selector)->text(), $message);
    }

    public function assertNotHtmlSelector(
        string $expected,
        string $document,
        string $selector,
        string $message = ''
    ): void
    {
        try {
            $value = (new Crawler($document))->filter($selector)->text();
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        $this->assertNotSame($expected, $value, $message);
    }

    public function assertHtmlSelectorContains(
        string $expected,
        string $document,
        string $selector,
        string $message = ''
    ): void
    {
        $this->assertContains($expected, (new Crawler($document))->filter($selector)->text(), $message);
    }

    public function assertNotHtmlSelectorContains(
        string $expected,
        string $document,
        string $selector,
        string $message = ''
    ): void
    {
        try {
            $value = (new Crawler($document))->filter($selector)->text();
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        $this->assertNotContains($expected, $value, $message);
    }

    public function assertHtmlSelectorAttribute(
        string $expected,
        string $document,
        string $selector,
        string $attribute,
        string $message = ''
    ): void
    {
        $this->assertSame($expected, (new Crawler($document))->filter($selector)->attr($attribute), $message);
    }

    public function assertHtmlSelectorAttributeContains(
        string $expected,
        string $document,
        string $selector,
        string $attribute,
        string $message = ''
    ): void
    {
        $this->assertContains($expected, (new Crawler($document))->filter($selector)->attr($attribute), $message);
    }

    public function assertNotHtmlSelectorAttribute(
        string $expected,
        string $document,
        string $selector,
        string $attribute,
        string $message = ''
    ): void
    {
        try {
            $value = (new Crawler($document))->filter($selector)->attr($attribute);
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        $this->assertNotSame($expected, $value, $message);
    }

    public function assertXpathSelector(string $expected, string $document, string $xpath, string $message = ''): void
    {
        $this->assertSame($expected, (new Crawler($document))->filterXPath($xpath)->text(), $message);
    }

    public function assertNotXpathSelector(
        string $expected,
        string $document,
        string $xpath,
        string $message = ''
    ): void
    {
        try {
            $value = (new Crawler($document))->filterXPath($xpath)->text();
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        $this->assertNotSame($expected, $value, $message);
    }

    public function assertXpathSelectorContains(
        string $expected,
        string $document,
        string $xpath,
        string $message = ''
    ): void
    {
        $this->assertContains($expected, (new Crawler($document))->filterXPath($xpath)->text(), $message);
    }

    public function assertNotXpathSelectorContains(
        string $expected,
        string $document,
        string $xpath,
        string $message = ''
    ): void
    {
        try {
            $value = (new Crawler($document))->filterXPath($xpath)->text();
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        $this->assertNotContains($expected, $value, $message);
    }

    public function assertXpathSelectorAttribute(
        string $expected,
        string $document,
        string $xpath,
        string $attribute,
        string $message = ''
    ): void
    {
        $this->assertSame($expected, (new Crawler($document))->filterXPath($xpath)->attr($attribute), $message);
    }

    public function assertNotXpathSelectorAttribute(
        string $expected,
        string $document,
        string $xpath,
        string $attribute,
        string $message = ''
    ): void
    {
        try {
            $value = (new Crawler($document))->filterXPath($xpath)->attr($attribute);
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        $this->assertNotSame($expected, $value, $message);
    }
}
