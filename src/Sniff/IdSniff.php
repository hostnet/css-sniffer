<?php
declare(strict_types=1);
/**
 * @copyright 2017 Hostnet B.V.
 */

namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\SniffInterface;
use Yannickl88\Component\CSS\Token;

final class IdSniff implements SniffInterface
{
    public function register(): array
    {
        return [
            Token::T_WORD,
        ];
    }

    public function process(File $file, int $stack_ptr): void
    {
        $token = $file->get($stack_ptr);

        if ($token->chars[0] === '#') {
            $id = $token->chars;

            // is there a class?
            if (false !== ($i = strpos($id, '.'))) {
                $id = substr($id, 0, $i);
            }

            if (1 !== preg_match('/^\#[a-z0-9-]+$/', $id)) {
                $file->addViolation(
                    'Id should only contain a-z, 0-9 and -.',
                    $token->lines[0],
                    $token->offsets[0],
                    $token->offsets[0] + strlen($id)
                );
            }
        }
    }
}
