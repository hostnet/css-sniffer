<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff;

use Hostnet\Component\CssSniff\Sniff\SingleStandard;
use PHPUnit\Framework\TestCase;
use Yannickl88\Component\CSS\Token;

/**
 * @covers \Hostnet\Component\CssSniff\Sniffer
 */
class SnifferTest extends TestCase
{
    /**
     * @var Sniffer
     */
    private $sniffer;

    protected function setUp()
    {
        $this->sniffer = new Sniffer();
    }

    public function testProcess()
    {
        $file = new File('phpunit', [
            new Token(Token::T_WORD, 'foobar', 1, 0),
            new Token(Token::T_WHITESPACE, ' ', 1, 6),
            new Token(Token::T_WORD, 'barbaz', 1, 7),
            new Token(Token::T_SEMICOLON, ';', 1, 13),
        ]);

        $this->sniffer->loadStandard($standard = SingleStandard::load(SniffStub::class));
        $this->sniffer->process([$file]);

        self::assertEquals([
            [$file, 0],
            [$file, 2],
            [$file, 3],
        ], $standard->getSniffs()[0]->getSniff()->process_calls);
    }
}
