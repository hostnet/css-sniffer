<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff;

use Yannickl88\Component\CSS\Token;

class SniffStub implements SniffInterface
{
    public $process_calls = [];

    public function register(): array
    {
        return [Token::T_WORD, Token::T_SEMICOLON];
    }

    public function process(File $file, int $stack_ptr): void
    {
        $this->process_calls[] = [$file, $stack_ptr];
    }
}
