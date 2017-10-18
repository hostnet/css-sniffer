<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff\Configuration;

use Hostnet\Component\CssSniff\File;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\CssSniff\Configuration\SingleFileConfiguration
 */
class SingleConfigurationTest extends TestCase
{
    /**
     * @var SingleFileConfiguration
     */
    private $single_file_configuration;

    protected function setUp()
    {
        $this->single_file_configuration = new SingleFileConfiguration(__DIR__ . '/test.less');
    }

    public function testGetFile()
    {
        self::assertInstanceOf(File::class, $this->single_file_configuration->getFile());
    }
}
