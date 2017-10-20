<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\CssSniff\Standard
 */
class StandardTest extends TestCase
{
    public function testLoadFromXmlFileSimple()
    {
        $standard = Standard::loadFromXmlFile(__DIR__ . '/fixtures/simple.xml.dist');

        self::assertEquals('simple', $standard->getName());
        self::assertCount(1, $standard->getSniffs());
    }

    public function testLoadFromXmlFileExtendRelative()
    {
        $standard = Standard::loadFromXmlFile(__DIR__ . '/fixtures/extend-relative.xml.dist');

        self::assertEquals('extend-relative', $standard->getName());
        self::assertCount(12, $standard->getSniffs());
    }

    public function testLoadFromXmlFileExtendDefault()
    {
        $standard = Standard::loadFromXmlFile(__DIR__ . '/fixtures/extend-default.xml.dist');

        self::assertEquals('extend-default', $standard->getName());
        self::assertCount(12, $standard->getSniffs());
    }
}
