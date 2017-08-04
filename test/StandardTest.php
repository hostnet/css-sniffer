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
    public function testLoadFromXmlFile()
    {
        $standard = Standard::loadFromXmlFile(__DIR__ . '/../src/Standard/Hostnet.xml');

        self::assertEquals('Hostnet', $standard->getName());
    }
}
