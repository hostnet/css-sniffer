<?php
declare(strict_types=1);
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\SniffInterface;
use Yannickl88\Component\CSS\Token;

final class CurlySniff implements SniffInterface
{
    public function register(): array
    {
        return [
            Token::T_OPENCURLY,
        ];
    }

    public function process(File $file, int $stack_ptr): void
    {
        $token = $file->get($stack_ptr);

        // do we have one line?
        [$contains_returns, $contains_multiple_statements, $closing_curly_index] = $this->isOneLine($file, $stack_ptr);

        $last_token = $file->get($closing_curly_index);

        // One line
        if (!$contains_returns) {
            if ($contains_multiple_statements) {
                $file->addViolation(
                    'Multiple statements found on one line.',
                    $token->lines[0],
                    $token->offsets[0],
                    $last_token->offsets[0] + strlen($last_token->chars)
                );

                // Ignore the spaces.
                return;
            }

            // Do not complain about empty blocks.
            if ($stack_ptr + 1 === $closing_curly_index) {
                return;
            }

            // Does the opening curly have a space after it?
            $next = $file->get($stack_ptr + 1);
            if ($next->type !== Token::T_WHITESPACE || $next->chars !== ' ') {
                $file->addViolation(
                    'Opening curly bracket should be follow by only one space.',
                    $token->lines[0],
                    $token->offsets[0],
                    $token->offsets[0] + strlen($token->chars)
                );
            }

            // Does the closing curly have a space before it?
            $previous = $file->get($closing_curly_index - 1);
            if ($previous->type !== Token::T_WHITESPACE || $previous->chars !== ' ') {
                $file->addViolation(
                    'Closing curly bracket should be proceeded by only one space.',
                    $last_token->lines[0],
                    $last_token->offsets[0],
                    $last_token->offsets[0] + strlen($last_token->chars)
                );
            }
        } elseif (!$contains_multiple_statements) {
            // Multiple lines
            $file->addViolation(
                'One statements found and should be on one line.',
                $token->lines[0],
                $token->offsets[0],
                $token->offsets[0] + strlen($token->chars)
            );
            return;
        }
    }

    private function isOneLine(File $file, int $stack_ptr): array
    {
        $c      = 0;
        $i      = $stack_ptr + 1;
        $tokens = $file->getTokens();
        $n      = count($tokens);

        $seen_semicolon               = false;
        $contains_returns             = false;
        $contains_multiple_statements = false;
        $closing_curly_index          = $i;

        do {
            $next = $tokens[$i];

            // Close } will break only if we are on the same level.
            if ($next->type === Token::T_CLOSECURLY) {
                if ($c > 0) {
                    $c--;
                    $i++;

                    continue;
                }

                $closing_curly_index = $i;

                break;
            }

            // Open { will raise the level.
            if ($next->type === Token::T_OPENCURLY) {
                $c++;
                $i++;

                continue;
            }

            if ($next->type === Token::T_WHITESPACE && false !== strpos($next->chars, "\n")) {
                $contains_returns = true;
            }

            if ($next->type === Token::T_SEMICOLON) {
                $contains_multiple_statements = $seen_semicolon;
                $seen_semicolon = true;
            }

            $i++;
        } while ($i < $n);

        return [$contains_returns, $contains_multiple_statements, $closing_curly_index];
    }
}
