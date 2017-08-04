<?php
declare(strict_types=1);
/**
 * @copyright 2015-2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff\Output;

/**
 * Console formatter, this returns the output as a list of errors.
 */
final class ConsoleFormatter implements FormatterInterface
{
    public function format(array $violations, bool $pretty = false): string
    {
        $out = '';

        foreach ($violations as $violation) {
            if (!empty($out)) {
                $out .= "\n";
            }

            $out .= sprintf('%s At line %d:%d.', $violation->getMsg(), $violation->getLine(), $violation->getStart());
        }

        return $out;
    }

    public function formatError($error, bool $pretty = false): string
    {
        return '';
    }
}
