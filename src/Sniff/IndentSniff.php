<?php
declare(strict_types=1);
/**
 * @copyright 2015-2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\SniffInterface;
use Yannickl88\Component\CSS\Token;

final class IndentSniff implements SniffInterface
{
    public function register(): array
    {
        return [
            Token::T_WHITESPACE,
        ];
    }

    public function process(File $file, int $stack_ptr): void
    {
        $token = $file->get($stack_ptr);

        // did we use spaces?
        if (false !== strpos($token->chars, "\t")) {
            $file->addViolation(
                'Line contains tabs, use only spaces.',
                $token->lines[0]
            );

            return;
        }

        if (1 === preg_match('/[\n\r\f]+/', $token->chars)) {
            $parts = preg_split('/[\n\r\f]+/', $token->chars);

            for ($i = 0, $n = count($parts); $i < $n; $i++) {
                if (strlen($parts[$i]) % 4 !== 0) {
                    $file->addViolation(
                        sprintf(
                            'Line not indented correctly, expected %s, got %s.',
                            ceil(strlen($parts[$i]) / 4) * 4,
                            strlen($parts[$i])
                        ),
                        $token->lines[0] + $i - 1
                    );
                }
            }
        } elseif ($token->offsets[0] === 0) {
            if (strlen($token->chars) % 4 !== 0) {
                $file->addViolation(
                    sprintf(
                        'Line not indented correctly, expected %s, got %s.',
                        ceil(strlen($token->chars) / 4) * 4,
                        strlen($token->chars)
                    ),
                    $token->lines[0]
                );
            }
        }
    }
}
