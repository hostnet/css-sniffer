<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\SniffInterface;
use Yannickl88\Component\CSS\Token;

final class EmptySniff implements SniffInterface
{
    public function register(): array
    {
        return [
            Token::T_OPENCURLY,
        ];
    }

    public function process(File $file, int $stack_ptr): void
    {
        $next = $file->findNextNot(Token::T_WHITESPACE, $stack_ptr + 1);

        if (null === $next || $next->type !== Token::T_CLOSECURLY) {
            return;
        }

        $file->addViolation(
            self::class,
            'CSS block should not be empty.',
            $next->lines[0],
            $next->offsets[0],
            $next->offsets[0] + strlen($next->chars)
        );
    }
}
