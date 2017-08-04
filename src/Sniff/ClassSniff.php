<?php
declare(strict_types=1);
/**
 * @copyright 2015-2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\SniffInterface;
use Yannickl88\Component\CSS\Token;

final class ClassSniff implements SniffInterface
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

        if (1 === preg_match('/^([&]?)(\..*)$/', $token->chars, $matches)) {
            $classes = array_slice(explode('.', $matches[2]), 1);

            $start = $token->offsets[0] + strlen($matches[1]);

            foreach ($classes as $class) {
                if (1 !== preg_match('/^[a-z0-9-]+$/', $class)) {
                    $file->addViolation(
                        'Class should only contain a-z, 0-9 and -.',
                        $token->lines[0],
                        $start,
                        $start + 1 + strlen($class)
                    );
                }

                $start += 1 + strlen($class);
            }

        }
    }
}
