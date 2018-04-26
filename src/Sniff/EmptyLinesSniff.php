<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\SniffInterface;
use Yannickl88\Component\CSS\Token;

/**
 * Checks that there are not more than two empty lines after each other. Only
 * one is allowed.
 */
final class EmptyLinesSniff implements SniffInterface
{
    private $newline_char;

    public function __construct(string $newline_char = "\n")
    {
        $this->newline_char = $newline_char;
    }

    public function register(): array
    {
        return [
            Token::T_WHITESPACE,
        ];
    }

    public function process(File $file, int $stack_ptr): void
    {
        $token = $file->get($stack_ptr);

        if (substr_count($token->chars, $this->newline_char) <= 2) {
            return;
        }

        $file->addViolation(
            self::class,
            'More than two new lines found.',
            $token->lines[0]
        );
    }
}
