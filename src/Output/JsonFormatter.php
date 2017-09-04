<?php
declare(strict_types=1);
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff\Output;

use Hostnet\Component\CssSniff\Violation;

/**
 * Json formatter.
 *
 * Returns violations as:
 * [
 *     {
 *         "msg": "...",
 *         "line": 1,
 *         "start": 2,
 *         "end": 42
 *     },
 *     // ...
 * ]
 */
final class JsonFormatter implements FormatterInterface
{
    public function format(array $violations, bool $pretty = false): string
    {
        return json_encode(array_map(function (Violation $v) {
            return [
                'msg' => $v->getMsg(),
                'line' => $v->getLine(),
                'start' => $v->getStart(),
                'end' => $v->getEnd(),
            ];
        }, $violations), $pretty ? JSON_PRETTY_PRINT : 0);
    }

    public function formatError($error, bool $pretty = false): string
    {
        return json_encode($error, $pretty ? JSON_PRETTY_PRINT : 0);
    }
}
