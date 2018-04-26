<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\SniffInterface;
use Yannickl88\Component\CSS\Token;

final class ClassSniff implements SniffInterface
{
    private $syntax;

    public function __construct(string $syntax = '[a-z0-9-]+')
    {
        $this->syntax = $syntax;
    }

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

            $this->checkClasses($file, $token->lines[0], $token->offsets[0] + strlen($matches[1]), $classes);
        } elseif (1 === preg_match('/^(#[^\.]+)(\..*)$/', $token->chars, $matches)) {
            $classes = array_slice(explode('.', $matches[2]), 1);

            $this->checkClasses($file, $token->lines[0], $token->offsets[0] + strlen($matches[1]), $classes);
        }
    }

    private function checkClasses(File $file, int $line, int $start, array $classes): void
    {
        foreach ($classes as $class) {
            if (!empty($class) && 1 !== preg_match('/^' . $this->syntax . '$/', $class)) {
                $file->addViolation(
                    self::class,
                    'Class should only contain a-z, 0-9 and -.',
                    $line,
                    $start,
                    $start + 1 + strlen($class)
                );
            }

            $start += 1 + strlen($class);
        }
    }
}
