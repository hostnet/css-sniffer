<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\SniffInterface;
use Yannickl88\Component\CSS\Token;

final class VariableSniff implements SniffInterface
{
    public function register(): array
    {
        return [
            Token::T_ATWORD,
        ];
    }

    public function process(File $file, int $stack_ptr): void
    {
        $token = $file->get($stack_ptr);

        if (1 === preg_match('/^@[a-z0-9_-]*$/', $token->chars)) {
            return;
        }

        $file->addViolation(
            self::class,
            'Variable should only contain a-z, 0-9, _ and -.',
            $token->lines[0],
            $token->offsets[0],
            $token->offsets[0] + strlen($token->chars)
        );
    }
}
