<?php
declare(strict_types=1);
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff;

use Yannickl88\Component\CSS\Token;

/**
 * Class which can be used for sniffing.
 */
final class File
{
    private $name;
    private $tokens;
    private $violations = [];

    /**
     * @param string  $name
     * @param Token[] $tokens
     */
    public function __construct(string $name, array $tokens)
    {
        $this->name   = $name;
        $this->tokens = $tokens;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Return all token.
     *
     * @return Token[]
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * Return the next token from the offset which has the given type.
     *
     * @param int $type
     * @param int $offset
     * @return null|Token
     */
    public function findNext(int $type, int $offset): ?Token
    {
        for ($n = count($this->tokens); $offset < $n; $offset++) {
            if ($this->tokens[$offset]->type === $type) {
                return $this->tokens[$offset];
            }
        }

        return null;
    }

    /**
     * Return the next token from the offset which does not have the given type.
     *
     * @param int $type
     * @param int $offset
     * @return null|Token
     */
    public function findNextNot(int $type, int $offset): ?Token
    {
        for ($n = count($this->tokens); $offset < $n; $offset++) {
            if ($this->tokens[$offset]->type !== $type) {
                return $this->tokens[$offset];
            }
        }

        return null;
    }

    /**
     * Return the token at the given offset.
     *
     * @param int $offset
     * @return Token
     * @throws \OutOfRangeException when offset is out of range
     */
    public function get(int $offset): Token
    {
        if ($offset < 0 || $offset >= count($this->tokens)) {
            throw new \OutOfRangeException(sprintf(
                'Offset should be between 0 and %d, got %d.',
                count($this->tokens),
                $offset
            ));
        }

        return $this->tokens[$offset];
    }

    /**
     * Add a violation to this file.
     *
     * @param string $source
     * @param string $msg
     * @param int    $line
     * @param int    $start
     * @param int    $end
     */
    public function addViolation(string $source, string $msg, int $line, int $start = 0, int $end = -1): void
    {
        $this->violations[] = new Violation($source, $msg, $line, $start, $end);
    }

    /**
     * Return the list of violations.
     *
     * @return Violation[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * Check if the file has had any violations.
     *
     * @return bool
     */
    public function isOk(): bool
    {
        return count($this->violations) === 0;
    }
}
