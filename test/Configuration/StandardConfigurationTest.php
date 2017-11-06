<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff\Configuration;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\Standard;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\CssSniff\Configuration\StandardConfiguration
 */
class StandardConfigurationTest extends TestCase
{
    /**
     * @var StandardConfiguration
     */
    private $null_configuration;

    protected function setUp()
    {
        $this->null_configuration = new StandardConfiguration(Standard::loadFromXmlFile(__DIR__ . '/test.xml.dist'));
    }

    public function testGetFile()
    {
        $files = array_map(function (File $f) {
            return $f->getName();
        }, $this->null_configuration->getFiles());

        sort($files);
        
        $ds = DIRECTORY_SEPARATOR;

        self::assertEquals([
            dirname(__DIR__) . $ds . 'Configuration'. $ds . 'test.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'args.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'bad_class.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'bad_colors.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'bad_variable.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'color_variants.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'comments.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'curly.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'empty.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'generated_class.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'indent.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'newlines.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'quotes.less',
            dirname(__DIR__) . $ds . 'Sniff'. $ds . 'fixtures' . $ds. 'semicolon.less',
        ], $files);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage File "foo" is not a file.
     */
    public function testGetFileBadFile()
    {
        (new StandardConfiguration(Standard::loadFromXmlFile(__DIR__ . '/bad-file.xml')))->getFiles();
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Directory "bar" is not a directory.
     */
    public function testGetFileBadDir()
    {
        (new StandardConfiguration(Standard::loadFromXmlFile(__DIR__ . '/bad-dir.xml')))->getFiles();
    }
}
