<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\SniffInterface;
use Yannickl88\Component\CSS\Token;

final class QuoteTypeSniff implements SniffInterface
{
    private $quote;

    public function __construct(string $quote = '"')
    {
        $this->quote = $quote;
    }

    public function register(): array
    {
        return [
            Token::T_STRING,
        ];
    }

    public function process(File $file, int $stack_ptr): void
    {
        $token = $file->get($stack_ptr);

        // Ignore backticks since it is inline JavaScript.
        // @see https://www.bennadel.com/blog/2638-executing-javascript-in-the-less-css-precompiler.htm
        if ($token->chars[0] === '`') {
            return;
        }

        if ($token->chars[0] === $this->quote && $token->chars[strlen($token->chars) - 1] === $this->quote) {
            return;
        }

        $file->addViolation(
            self::class,
            sprintf('Text should use %s as quotes.', $this->quote),
            $token->lines[0],
            $token->offsets[0],
            $token->offsets[0] + strlen($token->chars)
        );
    }
}
