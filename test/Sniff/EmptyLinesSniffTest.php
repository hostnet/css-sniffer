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
 * @covers \Hostnet\Component\CssSniff\Sniff\EmptyLinesSniff
 */
class EmptyLinesSniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->addSniff(new EmptyLinesSniff());
    }

    public function testSniff()
    {
        $file = new File((new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/newlines.less')));

        $this->sniffer->process($file);

        self::assertEquals([
            new Violation('More than two new lines found.', 3),
        ], $file->getViolations());
    }
}
