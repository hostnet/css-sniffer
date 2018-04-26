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
 * Check if the comments are not empty. Each comment should contain at least
 * one non-white space character or be removed.
 */
final class EmptyCommentSniff implements SniffInterface
{
    public function register(): array
    {
        return [
            Token::T_COMMENT,
        ];
    }

    public function process(File $file, int $stack_ptr): void
    {
        $token = $file->get($stack_ptr);

        // slash slash comment?
        if (0 === strpos($token->chars, '//')) {
            $comment = trim(substr($token->chars, 2));
        } else {
            preg_match('~/\*(.*)\*/~s', $token->chars, $matches);

            $comment = trim(implode("\n", array_map(function ($s) {
                return trim(1 === preg_match('~\s\**(.*)~', $s, $matches) ? $matches[1] : $s);
            }, explode("\n", $matches[1]))));
        }

        if ('' !== $comment) {
            return;
        }

        $lines = explode("\n", $token->chars);

        $file->addViolation(
            self::class,
            'Empty comment.',
            $token->lines[0],
            $token->offsets[0],
            $token->offsets[0] + strlen(trim($lines[0]))
        );
    }
}
