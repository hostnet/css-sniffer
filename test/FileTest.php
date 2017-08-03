<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff;

use PHPUnit\Framework\TestCase;
use Yannickl88\Component\CSS\Token;

/**
 * @covers \Hostnet\Component\CssSniff\File
 */
class FileTest extends TestCase
{
    public function testGeneric()
    {
        $file        = new File([
            $token_1 = new Token(Token::T_WORD, 'foobar', 1, 0),
            $token_2 = new Token(Token::T_WHITESPACE, ' ', 1, 6),
            $token_3 = new Token(Token::T_WORD, 'barbaz', 1, 7),
            $token_4 = new Token(Token::T_SEMICOLON, ';', 1, 13),
        ]);

        self::assertSame($token_1, $file->get(0));
        self::assertSame($token_2, $file->get(1));
        self::assertSame($token_3, $file->get(2));
        self::assertSame($token_4, $file->get(3));
        self::assertSame([$token_1, $token_2, $token_3, $token_4], $file->getTokens());
        self::assertSame($token_2, $file->findNext(Token::T_WHITESPACE, 1));
        self::assertSame($token_3, $file->findNext(Token::T_WORD, 1));
        self::assertSame($token_3, $file->findNextNot(Token::T_WHITESPACE, 1));
        self::assertSame($token_2, $file->findNextNot(Token::T_WORD, 1));
        self::assertTrue($file->isOk());
        $file->addViolation('foobar', 1);
        self::assertEquals([new Violation('foobar', 1)], $file->getViolations());
        self::assertFalse($file->isOk());
    }
}
