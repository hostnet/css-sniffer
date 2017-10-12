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
 * @covers \Hostnet\Component\CssSniff\Sniff\EmptyCommentSniff
 */
class EmptyCommentSniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->addSniff(new EmptyCommentSniff());
    }

    public function testSniff()
    {
        $file = new File((new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/comments.less')));

        $this->sniffer->process($file);

        self::assertEquals([
            new Violation(EmptyCommentSniff::class, 'Empty comment.', 3, 1, 3),
            new Violation(EmptyCommentSniff::class, 'Empty comment.', 7, 1, 3),
            new Violation(EmptyCommentSniff::class, 'Empty comment.', 10, 1, 3),
        ], $file->getViolations());
    }
}
