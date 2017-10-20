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
 * @covers \Hostnet\Component\CssSniff\Sniff\SemicolonSniff
 */
class SemicolonSniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->addSniff(new SemicolonSniff());
    }

    public function testSniff()
    {
        $file = new File(
            'phpunit',
            (new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/semicolon.less'))
        );

        $this->sniffer->process([$file]);

        self::assertEquals([
            new Violation(SemicolonSniff::class, 'Duplicate semicolon.', 2, 19, 20),
        ], $file->getViolations());
    }
}
