<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff\Output;

use Hostnet\Component\CssSniff\Violation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\CssSniff\Output\ConsoleFormatter
 */
class ConsoleFormatterTest extends TestCase
{
    /**
     * @var ConsoleFormatter
     */
    private $console_formatter;

    protected function setUp()
    {
        $this->console_formatter = new ConsoleFormatter();
    }

    public function testFormat()
    {
        self::assertSame(
            'foobar At line 1:0.',
            $this->console_formatter->format([new Violation('foobar', 1)])
        );
    }
}
