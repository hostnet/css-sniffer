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
 * @covers \Hostnet\Component\CssSniff\Sniff\QuoteTypeSniff
 */
class QuoteTypeSniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->addSniff(new QuoteTypeSniff());
    }

    public function testSniff()
    {
        $file = new File('phpunit', (new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/quotes.less')));

        $this->sniffer->process([$file]);

        self::assertEquals([
            new Violation(QuoteTypeSniff::class, 'Text should use " as quotes.', 5, 27, 37),
        ], $file->getViolations());
    }
}
