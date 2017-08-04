<?php
declare(strict_types=1);
/**
 * @copyright 2015-2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff\Output;

use Hostnet\Component\CssSniff\Violation;

interface FormatterInterface
{
    /**
     * @param Violation[] $violations
     * @param bool        $pretty
     * @return string
     */
    public function format(array $violations, bool $pretty = false): string;

    /**
     * @param mixed $error
     * @param bool  $pretty
     * @return string
     */
    public function formatError($error, bool $pretty = false): string;
}
