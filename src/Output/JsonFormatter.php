<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Output;

use Hostnet\Component\CssSniff\Violation;

/**
 * Json formatter.
 */
final class JsonFormatter implements FormatterInterface
{
    private $pretty_format;

    public function __construct(bool $pretty_format)
    {
        $this->pretty_format = $pretty_format;
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $files): string
    {
        $result = [
            'totals' => [
                'errors' => 0,
            ],
            'files' => [],
        ];

        foreach ($files as $file) {
            if ($file->isOk()) {
                continue;
            }

            $violations = $file->getViolations();

            $result['files'][$file->getName()] = [
                'errors' => count($violations),
                'messages' => array_map(function (Violation $v) {
                    return [
                        'message' => $v->getMsg(),
                        'source'  => $v->getSource(),
                        'line'    => $v->getLine(),
                        'column'  => $v->getStart(),
                    ];
                }, $violations),
            ];
        }

        return json_encode($result, $this->pretty_format ? JSON_PRETTY_PRINT : 0);
    }

    public function formatError(\Exception $error): string
    {
        return json_encode($error->getMessage(), $this->pretty_format ? JSON_PRETTY_PRINT : 0);
    }
}
