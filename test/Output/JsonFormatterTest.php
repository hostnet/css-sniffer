<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Output;

use Hostnet\Component\CssSniff\File;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\CssSniff\Output\JsonFormatter
 */
class JsonFormatterTest extends TestCase
{
    /**
     * @var JsonFormatter
     */
    private $json_formatter;

    protected function setUp()
    {
        $this->json_formatter = new JsonFormatter(false);
    }

    public function testFormat()
    {
        $file = new File('phpunit', []);
        $file->addViolation('phpunit', 'foobar', 1);

        self::assertStringEqualsFile(
            __DIR__ . '/output.json.txt',
            $this->json_formatter->format([$file])
        );
    }

    public function testFormatError()
    {
        self::assertSame(
            '"Some Error"',
            $this->json_formatter->formatError(new \RuntimeException('Some Error'))
        );
    }
}
