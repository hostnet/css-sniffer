<?php
declare(strict_types=1);
/**
 * @copyright 2015-2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff;

/**
 * Generic violation. This is something that does not correspond to the
 * code-style.
 */
final class Violation
{
    private $msg;
    private $line;
    private $start;
    private $end;

    public function __construct(string $msg, int $line, int $start = 0, int $end = -1)
    {
        $this->msg   = $msg;
        $this->line  = $line;
        $this->start = $start;
        $this->end   = $end;
    }

    /**
     * Return the violation message.
     *
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * Return the violation line number.
     *
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * Return the violation starting character.
     *
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * Return the violation ending character. If this is -1 is should be the
     * end of that line.
     *
     * @return int
     */
    public function getEnd(): int
    {
        return $this->end;
    }
}
