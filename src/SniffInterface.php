<?php
declare(strict_types=1);
/**
 * @copyright 2017 Hostnet B.V.
 */

namespace Hostnet\Component\CssSniff;

/**
 * Implementations of this interface can add violations to a file.
 */
interface SniffInterface
{
    /**
     * Return the Token types for which this sniff should trigger.
     *
     * @return array
     */
    public function register(): array;

    /**
     * Process a triggered sniff.
     *
     * @param File $file
     * @param int  $stack_ptr
     */
    public function process(File $file, int $stack_ptr): void;
}
