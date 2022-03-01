<?php
namespace InterNations\Component\Testing;

use Symfony\Component\DomCrawler\Crawler;
use InvalidArgumentException;

trait DomCrawlerAssertionTrait
{
    protected static function assertHtmlSelector(
        string $expected,
        string $document,
        string $selector,
        string $message = ''
    ): void
    {
        self::assertSame($expected, (new Crawler($document))->filter($selector)->text(null, true), $message);
    }

    protected static function assertNotHtmlSelector(
        string $expected,
        string $document,
        string $selector,
        string $message = ''
    ): void
    {
        try {
            $value = (new Crawler($document))->filter($selector)->text(null, true);
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        self::assertNotSame($expected, $value, $message);
    }

    protected static function assertHtmlSelectorContains(
        string $expected,
        string $document,
        string $selector,
        string $message = ''
    ): void
    {
        self::assertStringContainsString($expected, (new Crawler($document))->filter($selector)->text(null, true), $message);
    }

    protected static function assertNotHtmlSelectorContains(
        string $expected,
        string $document,
        string $selector,
        string $message = ''
    ): void
    {
        try {
            $value = (new Crawler($document))->filter($selector)->text(null, true);
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        self::assertNotContains($expected, [$value], $message);
    }

    protected static function assertHtmlSelectorAttribute(
        string $expected,
        string $document,
        string $selector,
        string $attribute,
        string $message = ''
    ): void
    {
        self::assertSame($expected, (new Crawler($document))->filter($selector)->attr($attribute), $message);
    }

    protected static function assertHtmlSelectorAttributeContains(
        string $expected,
        string $document,
        string $selector,
        string $attribute,
        string $message = ''
    ): void
    {
        self::assertContains($expected, [(new Crawler($document))->filter($selector)->attr($attribute)], $message);
    }

    protected static function assertNotHtmlSelectorAttribute(
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
        self::assertNotSame($expected, $value, $message);
    }

    protected static function assertXpathSelector(
        string $expected,
        string $document,
        string $xpath,
        string $message = ''
    ): void
    {
        self::assertSame($expected, (new Crawler($document))->filterXPath($xpath)->text(null, true), $message);
    }

    protected static function assertNotXpathSelector(
        string $expected,
        string $document,
        string $xpath,
        string $message = ''
    ): void
    {
        try {
            $value = (new Crawler($document))->filterXPath($xpath)->text(null, true);
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        self::assertNotSame($expected, $value, $message);
    }

    protected static function assertXpathSelectorContains(
        string $expected,
        string $document,
        string $xpath,
        string $message = ''
    ): void
    {
        self::assertStringContainsString($expected, (new Crawler($document))->filterXPath($xpath)->text(null, true), $message);
    }

    protected static function assertNotXpathSelectorContains(
        string $expected,
        string $document,
        string $xpath,
        string $message = ''
    ): void
    {
        try {
            $value = (new Crawler($document))->filterXPath($xpath)->text(null, true);
        } catch (InvalidArgumentException $e) {
            $value = '';
        }
        self::assertNotContains($expected, [$value], $message);
    }

    protected static function assertXpathSelectorAttribute(
        string $expected,
        string $document,
        string $xpath,
        string $attribute,
        string $message = ''
    ): void
    {
        self::assertSame($expected, (new Crawler($document))->filterXPath($xpath)->attr($attribute), $message);
    }

    protected static function assertNotXpathSelectorAttribute(
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
        self::assertNotSame($expected, $value, $message);
    }
}
