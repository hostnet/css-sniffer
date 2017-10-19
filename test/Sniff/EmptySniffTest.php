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
 * @covers \Hostnet\Component\CssSniff\Sniff\EmptySniff
 */
class EmptySniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->addSniff(new EmptySniff());
    }

    public function testSniff()
    {
        $file = new File('phpunit', (new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/empty.less')));

        $this->sniffer->process($file);

        self::assertEquals([
            new Violation(EmptySniff::class, 'CSS block should not be empty.', 1, 9, 10),
            new Violation(EmptySniff::class, 'CSS block should not be empty.', 4, 1, 2),
            new Violation(EmptySniff::class, 'CSS block should not be empty.', 11, 1, 2),
        ], $file->getViolations());
    }
}
