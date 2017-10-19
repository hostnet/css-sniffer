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
 * @covers \Hostnet\Component\CssSniff\Sniff\ArgsSniff
 */
class ArgsSniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->addSniff(new ArgsSniff());
    }

    public function testSniff()
    {
        $file = new File('phpunit', (new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/args.less')));

        $this->sniffer->process($file);

        self::assertEquals([
            new Violation(ArgsSniff::class, 'Comma should be followed by 1 and no more spaces.', 2, 10, 11),
            new Violation(ArgsSniff::class, 'Comma should be followed by 1 and no more spaces.', 3, 10, 11),
        ], $file->getViolations());
    }
}
