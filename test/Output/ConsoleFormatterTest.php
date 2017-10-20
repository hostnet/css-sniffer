<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff\Output;

use Hostnet\Component\CssSniff\File;
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
        $this->console_formatter = new ConsoleFormatter(false);
    }

    public function testFormat()
    {
        $file = new File('phpunit', []);
        $file->addViolation('phpunit', 'foobar', 1);

        self::assertStringEqualsFile(
            __DIR__ . '/output.console.txt',
            $this->console_formatter->format([$file])
        );
    }

    public function testFormatLongText()
    {
        $file = new File('phpunit', []);
        $file->addViolation(
            'phpunit',
            'this is a very long violoation message which require to be split into multple lines so that it can be '.
            'read more properly without overflowing to outside of the table. Because if it would do that it becomes '.
            'very difficult to read on smaller screens.',
            1
        );

        self::assertStringEqualsFile(
            __DIR__ . '/long.console.txt',
            $this->console_formatter->format([$file])
        );
    }

    public function testFormatOrder()
    {
        $file = new File('phpunit', []);
        $file->addViolation('phpunit', 'barbaz', 2);
        $file->addViolation('phpunit', 'foobar', 1);

        self::assertStringEqualsFile(
            __DIR__ . '/ordered.console.txt',
            $this->console_formatter->format([$file])
        );
    }

    public function testFormatError()
    {
        self::assertSame(
            'Some Error',
            $this->console_formatter->formatError(new \RuntimeException('Some Error'))
        );
    }
}
