<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff\Configuration;

use Hostnet\Component\CssSniff\File;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\CssSniff\Configuration\NullConfiguration
 */
class NullConfigurationTest extends TestCase
{
    /**
     * @var NullConfiguration
     */
    private $null_configuration;

    protected function setUp()
    {
        $this->null_configuration = new NullConfiguration();
    }

    public function testGetFile()
    {
        self::assertEmpty($this->null_configuration->getFiles());
    }
}
