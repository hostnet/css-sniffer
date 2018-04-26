<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\SniffInterface;
use Yannickl88\Component\CSS\Token;

final class SemicolonSniff implements SniffInterface
{
    public function register(): array
    {
        return [
            Token::T_SEMICOLON,
        ];
    }

    public function process(File $file, int $stack_ptr): void
    {
        try {
            $next = $file->get($stack_ptr + 1);
        } catch (\OutOfRangeException $e) {
            return;
        }

        if ($next->type !== Token::T_SEMICOLON) {
            return;
        }

        $file->addViolation(
            self::class,
            'Duplicate semicolon.',
            $next->lines[0],
            $next->offsets[0],
            $next->offsets[0] + strlen($next->chars)
        );
    }
}
