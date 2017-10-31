<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff\Configuration;

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
        $this->null_configuration = new StandardConfiguration(Standard::loadFromXmlFile('Hostnet'));
    }

    public function testGetFile()
    {
        self::assertEmpty($this->null_configuration->getFiles());
    }
}
