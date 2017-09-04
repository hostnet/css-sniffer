<?php
declare(strict_types=1);
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\SniffInterface;
use Yannickl88\Component\CSS\Token;

final class ArgsSniff implements SniffInterface
{
    public function register(): array
    {
        return [
            Token::T_OPENBRACKET,
        ];
    }

    public function process(File $file, int $stack_ptr): void
    {
        $c     = 0;
        $i     = $stack_ptr + 1;
        $token = $file->getTokens();

        do {
            $next = $token[$i];

            // Close ) will break only if we are on the same level.
            if ($next->type === Token::T_CLOSEBRACKET) {
                if ($c > 0) {
                    $c--;
                    $i++;

                    continue;
                }

                break;
            }

            // Open ( will raise the level.
            if ($next->type === Token::T_OPENBRACKET) {
                $c++;
                $i++;

                continue;
            }

            if ($c === 0 && $next->type === Token::T_COMMA) {
                $next_space = $token[$i + 1];

                if ($next_space->chars !== ' ') {
                    $file->addViolation(
                        'Comma should be followed by 1 and no more spaces.',
                        $next->lines[0],
                        $next->offsets[0],
                        $next->offsets[0] + 1
                    );
                }
            }

            $i++;
        } while ($i < count($token));
    }
}
