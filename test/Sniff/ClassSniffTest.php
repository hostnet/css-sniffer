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
 * @covers \Hostnet\Component\CssSniff\Sniff\ClassSniff
 */
class ClassSniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->addSniff(new ClassSniff());
    }

    public function testSniff()
    {
        $file = new File((new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/bad_class.less')));

        $this->sniffer->process($file);

        self::assertEquals([
            new Violation('Class should only contain a-z, 0-9 and -.', 1, 1, 10),
            new Violation('Class should only contain a-z, 0-9 and -.', 2, 1, 9),
            new Violation('Class should only contain a-z, 0-9 and -.', 5, 7, 16),
            new Violation('Class should only contain a-z, 0-9 and -.', 7, 9, 19),
        ], $file->getViolations());
    }

    public function testSniffWithVariables()
    {
        $file = new File((new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/generated_class.less')));

        $this->sniffer->process($file);

        self::assertEmpty($file->getViolations());
    }
}
