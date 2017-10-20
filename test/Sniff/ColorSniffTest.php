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
 * @covers \Hostnet\Component\CssSniff\Sniff\ColorSniff
 */
class ColorSniffTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
        $this->sniffer->addSniff(new ColorSniff());
    }

    public function testSniff()
    {
        $file = new File(
            'phpunit',
            (new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/bad_colors.less'))
        );

        $this->sniffer->process([$file]);

        self::assertEquals([
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 4, 13, 17),
        ], $file->getViolations());
    }

    public function testSniffVariants()
    {
        $file = new File(
            'phpunit',
            (new Tokenizer())->tokenize(file_get_contents(__DIR__ . '/fixtures/color_variants.less'))
        );

        $this->sniffer->process([$file]);

        self::assertEquals([
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 2, 12, 16),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 3, 17, 21),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 4, 23, 27),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 5, 13, 17),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 6, 20, 24),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 7, 26, 30),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 8, 19, 23),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 9, 18, 22),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 10, 24, 28),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 11, 19, 23),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 12, 25, 29),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 13, 17, 21),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 14, 23, 27),
            new Violation(ColorSniff::class, 'Colors should always be 6 characters hex values.', 15, 17, 21),
        ], $file->getViolations());
    }
}
