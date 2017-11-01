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
 * @covers \Hostnet\Component\CssSniff\SniffConfiguration
 */
class SniffConfigurationTest extends TestCase
{
    private $sniff;
    private $exclusion_patterns;

    /**
     * @var SniffConfiguration
     */
    private $sniff_configuration;

    protected function setUp()
    {
        $this->sniff              = $this->prophesize(SniffInterface::class);
        $this->exclusion_patterns = ['`bar`i'];

        $this->sniff_configuration = new SniffConfiguration(
            $this->sniff->reveal(),
            $this->exclusion_patterns
        );
    }

    public function testGetSniff()
    {
        self::assertSame($this->sniff->reveal(), $this->sniff_configuration->getSniff());
    }

    /**
     * @dataProvider shouldSniffProvider
     */
    public function testShouldSniff(bool $expected, File $file)
    {
        self::assertSame($expected, $this->sniff_configuration->shouldSniff($file));
    }

    public function shouldSniffProvider()
    {
        return [
            [true, new File('foo.less', [])],
            [true, new File('some/folder/foo.less', [])],
            [true, new File('some\\folder\\foo.less', [])],
            [false, new File('bar.less', [])],
            [false, new File('some/folder/bar.less', [])],
            [false, new File('some\\folder\\bar.less', [])],
        ];
    }

    public function testExtend()
    {
        $a = new SniffConfiguration(new ClassSniff(), ['`foo`i']);
        $b = new SniffConfiguration(new ClassSniff('[a-z]+'), []);

        $a->extend($b);

        self::assertEquals(new SniffConfiguration(new ClassSniff('[a-z]+'), ['`foo`i']), $a);
    }

    public function testExtendExclusionRules()
    {
        $a = new SniffConfiguration(new ClassSniff('[a-z]+'), ['`foo`i']);
        $b = new SniffConfiguration(new ClassSniff(), ['`bar`i']);

        $a->extend($b);

        self::assertEquals(new SniffConfiguration(new ClassSniff(), ['`foo`i', '`bar`i']), $a);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Cannot merge SniffConfiguration which have different sniffs.
     */
    public function testExtendBadSniffs()
    {
        $a = new SniffConfiguration(new ClassSniff(), []);
        $b = new SniffConfiguration(new ArgsSniff(), []);

        $a->extend($b);
    }
}
