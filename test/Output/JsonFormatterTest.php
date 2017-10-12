<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff\Output;

use Hostnet\Component\CssSniff\Violation;
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
        $this->json_formatter = new JsonFormatter();
    }

    public function testFormat()
    {
        self::assertSame(
            '[{"msg":"foobar","line":1,"start":0,"end":-1}]',
            $this->json_formatter->format([new Violation('phpunit', 'foobar', 1)])
        );
    }

    public function testFormatError()
    {
        self::assertSame(
            '"Some Error"',
            $this->json_formatter->formatError('Some Error')
        );
    }
}
