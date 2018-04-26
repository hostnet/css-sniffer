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
 * @covers \Hostnet\Component\CssSniff\Sniff\ColonSniff
 */
class ColonSniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->loadStandard(SingleStandard::load(ColonSniff::class));
    }

    public function testSniff()
    {
        $file = new File(
            'phpunit',
            (new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/colons.less'))
        );

        $this->sniffer->process([$file]);

        self::assertEquals([
            new Violation(ColonSniff::class, 'Colon should be followed by a single space.', 2, 10, 11),
            new Violation(ColonSniff::class, 'Colon should be followed by a single space.', 3, 11, 12),
        ], $file->getViolations());
    }
}
