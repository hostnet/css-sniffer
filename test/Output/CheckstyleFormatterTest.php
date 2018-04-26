<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Output;

use Hostnet\Component\CssSniff\File;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\CssSniff\Output\CheckstyleFormatter
 */
class CheckstyleFormatterTest extends TestCase
{
    /**
     * @var CheckstyleFormatter
     */
    private $checkstyle_formatter;

    protected function setUp()
    {
        $this->checkstyle_formatter = new CheckstyleFormatter(false);
    }

    public function testFormat()
    {
        $file = new File('phpunit', []);
        $file->addViolation('phpunit', 'foobar', 1);

        self::assertStringEqualsFile(
            __DIR__ . '/output.checkstyle.txt',
            $this->checkstyle_formatter->format([$file])
        );
    }

    public function testFormatError()
    {
        self::assertSame(
            'Some Error',
            $this->checkstyle_formatter->formatError(new \RuntimeException('Some Error'))
        );
    }
}
