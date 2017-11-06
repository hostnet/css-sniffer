<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\Sniffer;
use Hostnet\Component\CssSniff\Violation;
use PHPUnit\Framework\TestCase;
use Yannickl88\Component\CSS\Tokenizer;

/**
 * @covers \Hostnet\Component\CssSniff\Sniff\CurlySniff
 */
class CurlySniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->loadStandard(SingleStandard::load(CurlySniff::class));
    }

    public function testSniff()
    {
        $file = new File('phpunit', (new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/curly.less')));

        $this->sniffer->process([$file]);

        self::assertEquals([
            new Violation(CurlySniff::class, 'Opening curly bracket should be follow by only one space.', 6, 7, 8),
            new Violation(CurlySniff::class, 'Closing curly bracket should be proceeded by only one space.', 7, 24, 25),
            new Violation(CurlySniff::class, 'Opening curly bracket should be follow by only one space.', 8, 7, 8),
            new Violation(CurlySniff::class, 'Closing curly bracket should be proceeded by only one space.', 8, 27, 28),
            new Violation(CurlySniff::class, 'Multiple statements found on one line.', 9, 13, 57),
            new Violation(CurlySniff::class, 'One statements found and should be on one line.', 10, 13, 14),
            new Violation(
                CurlySniff::class,
                'Statement found on same line as opening bracket when expected on new line.',
                13,
                13,
                14
            ),
            new Violation(
                CurlySniff::class,
                'Statement found on same line as closing bracket when expected on new line.',
                18,
                30,
                31
            ),
            new Violation(CurlySniff::class, 'Media query should always be one multiple lines.', 36, 50, 51),
            new Violation(CurlySniff::class, 'Media query should always be one multiple lines.', 37, 52, 53),
        ], $file->getViolations());
    }
}
