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
 * @covers \Hostnet\Component\CssSniff\Sniff\IndentSniff
 */
class IndentSniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->loadStandard(SingleStandard::load(IndentSniff::class));
    }

    public function testSniff()
    {
        $file = new File('phpunit', (new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/indent.less')));

        $this->sniffer->process([$file]);

        self::assertEquals([
            new Violation(IndentSniff::class, 'Line not indented correctly, expected 4, got 2.', 5, 0, -1),
            new Violation(IndentSniff::class, 'Line contains tabs, use only spaces.', 8, 0, -1),
        ], $file->getViolations());
    }
}
