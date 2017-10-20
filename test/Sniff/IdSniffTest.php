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
 * @covers \Hostnet\Component\CssSniff\Sniff\IdSniff
 */
class IdSniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->addSniff(new IdSniff());
    }

    public function testSniff()
    {
        $file = new File('phpunit', (new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/bad_ids.less')));

        $this->sniffer->process([$file]);

        self::assertEquals([
            new Violation(IdSniff::class, 'Id should only contain a-z, 0-9 and -.', 1, 1, 10),
            new Violation(IdSniff::class, 'Id should only contain a-z, 0-9 and -.', 2, 1, 9),
            new Violation(IdSniff::class, 'Id should only contain a-z, 0-9 and -.', 5, 1, 10),
        ], $file->getViolations());
    }
}
