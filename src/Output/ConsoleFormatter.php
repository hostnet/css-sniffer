<?php
declare(strict_types=1);
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff\Output;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\Violation;

/**
 * Console formatter, this returns the output as a list of errors.
 */
final class ConsoleFormatter implements FormatterInterface
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
        $out = '';

        usort($files, function (File $a, File $b) {
            return $a->getName() <=> $b->getName();
        });

        foreach ($files as $file) {
            if ($file->isOk()) {
                continue;
            }

            $out .= sprintf("\nFILE: %s\n", $file->getName());

            $violations = $file->getViolations();

            // Make sure to sort the violations by line.
            usort($violations, function (Violation $a, Violation $b) {
                return $a->getLine() <=> $b->getLine();
            });

            $out .= "--------------------------------------------------------------------------------\n";
            $out .= sprintf(
                "FOUND %d ERROR(S) AFFECTING %d LINE(S)\n",
                count($violations),
                count(array_count_values(array_map(function (Violation $v) {
                    return $v->getLine();
                }, $violations)))
            );
            $out .= "--------------------------------------------------------------------------------\n";

            $size = array_reduce($violations, function (int $total, Violation $v) {
                return max($total, strlen((string) $v->getLine()));
            }, 0);
            $message_size = 80 - ($size + 4);

            foreach ($violations as $violation) {
                foreach ($this->splitMessage($violation->getMsg(), $message_size) as $i => $line) {
                    $line_no = $i === 0 ? (string) $violation->getLine() : '';

                    $out .= sprintf(
                        "%s | %s\n",
                        str_pad($line_no, $size + 1, ' ', STR_PAD_LEFT),
                        $line
                    );
                }
            }

            $out .= "--------------------------------------------------------------------------------\n";
        }

        return $out;
    }

    /**
     * {@inheritdoc}
     */
    public function formatError(\Exception $error): string
    {
        return $error->getMessage();
    }

    private function splitMessage(string $body, int $max_length): array
    {
        $words        = explode(' ', $body);
        $lines        = [];
        $current_line = '';

        foreach ($words as $word) {
            $appendable_word = '';
            if (!empty($current_line)) {
                $appendable_word .= ' ';
            }
            $appendable_word .= $word;

            if (strlen($current_line) + strlen($appendable_word) > $max_length) {
                $lines[]      = $current_line;
                $current_line = $word;
            } else {
                $current_line .= $appendable_word;
            }
        }

        if (!empty($current_line)) {
            $lines[] = $current_line;
        }

        return $lines;
    }
}
