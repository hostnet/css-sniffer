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
        [$is_part_of_variable] = $this->isPartOfVariable($file->getTokens(), $stack_ptr);

        if ($is_part_of_variable) {
            return;
        }

        $token = $file->get($stack_ptr);

        // do we have one line?
        [
            $contains_returns,
            $contains_multiple_statements,
            $is_media_query,
            $closing_curly_index
        ] = $this->isOneLine($file, $stack_ptr);

        $last_token = $file->get($closing_curly_index);

        // One line
        if (!$contains_returns) {
            if ($contains_multiple_statements) {
                if ($is_media_query) {
                    $file->addViolation(
                        self::class,
                        'Media query should always be one multiple lines.',
                        $token->lines[0],
                        $token->offsets[0],
                        $token->offsets[0] + strlen($token->chars)
                    );
                    return;
                }

                $file->addViolation(
                    self::class,
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
                    self::class,
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
                    self::class,
                    'Closing curly bracket should be proceeded by only one space.',
                    $last_token->lines[0],
                    $last_token->offsets[0],
                    $last_token->offsets[0] + strlen($last_token->chars)
                );
            }
        } elseif (!$contains_multiple_statements) {
            if ($is_media_query) {
                return;
            }

            // Multiple lines
            $file->addViolation(
                self::class,
                'One statements found and should be on one line.',
                $token->lines[0],
                $token->offsets[0],
                $token->offsets[0] + strlen($token->chars)
            );
            return;
        } else {
            try {
                $whitespace = $file->get($stack_ptr + 1)->chars;
            } catch (\OutOfRangeException $e) {
                return;
            }

            // Make sure there is a return after the first curly.
            if (false === strpos($whitespace, "\n")) {
                $file->addViolation(
                    self::class,
                    'Statement found on same line as opening bracket when expected on new line.',
                    $token->lines[0],
                    $token->offsets[0],
                    $token->offsets[0] + strlen($token->chars)
                );
                return;
            }

            // Make sure there is a return before the end of the last curly.
            $whitespace = $file->get($closing_curly_index - 1)->chars;

            if (false === strpos($whitespace, "\n")) {
                $close_curly = $file->get($closing_curly_index);

                $file->addViolation(
                    self::class,
                    'Statement found on same line as closing bracket when expected on new line.',
                    $close_curly->lines[0],
                    $close_curly->offsets[0],
                    $close_curly->offsets[0] + strlen($close_curly->chars)
                );
                return;
            }
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

        // Look ahead.
        do {
            $next = $tokens[$i];

            // Close } will break only if we are on the same level.
            if ($next->type === Token::T_CLOSECURLY) {
                if ($c > 0) {
                    $c--;
                    $i++;
                    $contains_multiple_statements = true;

                    continue;
                }

                $closing_curly_index = $i;

                break;
            }

            // Open { will raise the level.
            if ($next->type === Token::T_OPENCURLY) {
                // was it a variable?
                [$is_part_of_variable, $start, $end] = $this->isPartOfVariable($tokens, $i);

                if ($is_part_of_variable) {
                    $i = $end + 1;
                } else {
                    $c++;
                    $i++;
                }
                continue;
            }

            if ($next->type === Token::T_WHITESPACE && false !== strpos($next->chars, "\n")) {
                $contains_returns = true;
            }

            if ($next->type === Token::T_SEMICOLON) {
                $contains_multiple_statements = $seen_semicolon;
                $seen_semicolon               = true;
            }

            $i++;
        } while ($i < $n);

        $i    = $stack_ptr - 1;
        $line = '';

        // Look behind.
        do {
            $prev = $tokens[$i];

            if (in_array($prev->type, [Token::T_SEMICOLON, Token::T_COMMENT], true)) {
                break;
            }
            if (in_array($prev->type, [Token::T_CLOSECURLY, Token::T_OPENCURLY], true)) {
                [$is_part_of_variable, $start, $end] = $this->isPartOfVariable($tokens, $i);

                if ($is_part_of_variable) {
                    $i = $start - 1;

                    for (; $end >= $start; $end--) {
                        $line = $tokens[$end]->chars . $line;
                    }
                    continue;
                }
                break;
            }

            $line = $prev->chars . $line;

            $i--;
        } while ($i > 0);

        return [
            $contains_returns,
            $contains_multiple_statements,
            1 === preg_match('/^@media\s/', trim($line)),
            $closing_curly_index
        ];
    }

    private function isPartOfVariable(array $tokens, int $stack_ptr): array
    {
        $curly_open = $stack_ptr;

        while ($tokens[$curly_open]->type !== Token::T_OPENCURLY && $curly_open > 0) {
            $curly_open--;
        }

        $prev = $tokens[$curly_open - 1];

        if ($prev->type !== Token::T_ATWORD) {
            // Not part of variable.
            return [false, null, null];
        }

        $i = $curly_open + 1;
        $n = count($tokens);
        do {
            if ($tokens[$i]->type === Token::T_CLOSECURLY) {
                return [true, $curly_open - 1, $i];
            }

            $i++;
        } while ($i < $n);

        return [false, null, null];
    }
}
