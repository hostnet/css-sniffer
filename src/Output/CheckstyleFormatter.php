<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Output;

/**
 * Checkstyle xml formatter
 */
final class CheckstyleFormatter implements FormatterInterface
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
        $out  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $out .= "<checkstyle version=\"1.0.0\">\n";

        foreach ($files as $file) {
            if ($file->isOk()) {
                continue;
            }

            $out .= sprintf("  <file name=\"%s\">\n", $file->getName());

            foreach ($file->getViolations() as $violation) {
                $out .= sprintf(
                    "    <error line=\"%d\" column=\"%d\" severity=\"error\" message=\"%s\" source=\"%s\"/>\n",
                    $violation->getLine(),
                    $violation->getStart(),
                    htmlspecialchars($violation->getMsg()),
                    htmlspecialchars($violation->getSource())
                );
            }

            $out .= "  </file>\n";
        }

        $out .= "</checkstyle>\n";

        return $out;
    }

    /**
     * {@inheritdoc}
     */
    public function formatError(\Exception $error): string
    {
        return $error->getMessage();
    }
}
