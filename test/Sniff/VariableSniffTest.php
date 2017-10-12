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
 * @covers \Hostnet\Component\CssSniff\Sniff\VariableSniff
 */
class VariableSniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->addSniff(new VariableSniff());
    }

    public function testSniff()
    {
        $file = new File((new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/bad_variable.less')));

        $this->sniffer->process($file);

        self::assertEquals([
            new Violation(VariableSniff::class, 'Variable should only contain a-z, 0-9, _ and -.', 4, 1, 9),
        ], $file->getViolations());
    }

    public function testSniffWithVariables()
    {
        $file = new File((new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/generated_class.less')));

        $this->sniffer->process($file);

        self::assertEmpty($file->getViolations());
    }
}
