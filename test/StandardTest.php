<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff;

use Hostnet\Component\CssSniff\Sniff\ArgsSniff;
use Hostnet\Component\CssSniff\Sniff\ClassSniff;
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
        self::assertCount(2, $standard->getSniffs());
        self::assertSame(['foo.css'], $standard->getFiles());
        self::assertSame(['bar'], $standard->getDirectories());
        self::assertSame(['`baz`i'], $standard->getExclusionPatterns());
    }

    public function testLoadFromXmlFileExtendRelative()
    {
        $standard = Standard::loadFromXmlFile(__DIR__ . '/fixtures/extend-relative.xml.dist');

        self::assertEquals('extend-relative', $standard->getName());
        self::assertCount(13, $standard->getSniffs());
    }

    public function testLoadFromXmlFileExtendDefault()
    {
        $standard = Standard::loadFromXmlFile(__DIR__ . '/fixtures/extend-default.xml.dist');

        $sniffs = $standard->getSniffs();

        self::assertEquals('extend-default', $standard->getName());
        self::assertCount(13, $sniffs);
        self::assertEquals(new SniffConfiguration(new ArgsSniff(), ['`color\.less`i']), $sniffs[0]);
        self::assertEquals(new SniffConfiguration(new ClassSniff('[a-z]+'), []), $sniffs[1]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Cannot find standards file "foobar".
     */
    public function testLoadFromXmlFileBadStandard()
    {
        Standard::loadFromXmlFile('foobar');
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Missing class attribute for sniff.
     */
    public function testLoadFromXmlFileNoArgument()
    {
        Standard::loadFromXmlFile(__DIR__ . '/fixtures/no-argument.xml.dist');
    }
}
