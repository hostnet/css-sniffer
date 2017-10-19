<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff;

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
        $sniff = $this->prophesize(SniffInterface::class);
        $file  = new File('phpunit', [
            new Token(Token::T_WORD, 'foobar', 1, 0),
            new Token(Token::T_WHITESPACE, ' ', 1, 6),
            new Token(Token::T_WORD, 'barbaz', 1, 7),
            new Token(Token::T_SEMICOLON, ';', 1, 13),
        ]);

        $sniff->register()->willReturn([Token::T_WORD, Token::T_SEMICOLON]);
        $sniff->process($file, 0)->shouldBeCalled();
        $sniff->process($file, 2)->shouldBeCalled();
        $sniff->process($file, 3)->shouldBeCalled();

        $this->sniffer->addSniff($sniff->reveal());
        $this->sniffer->process($file);
    }
}
