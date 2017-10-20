<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff\Configuration;

use Hostnet\Component\CssSniff\File;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\CssSniff\Configuration\CliConfiguration
 */
class SingleConfigurationTest extends TestCase
{
    /**
     * @var CliConfiguration
     */
    private $single_file_configuration;

    protected function setUp()
    {
        $this->single_file_configuration = new CliConfiguration([__DIR__ . '/test.less']);
    }

    public function testGetFile()
    {
        $files = $this->single_file_configuration->getFiles();

        self::assertCount(1, $files);
        self::assertInstanceOf(File::class, $files[0]);
    }
}
