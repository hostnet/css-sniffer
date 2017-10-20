<?php
declare(strict_types=1);
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff\Output;

use Hostnet\Component\CssSniff\File;

interface FormatterInterface
{
    /**
     * @param File[] $files
     * @return string
     */
    public function format(array $files): string;

    /**
     * @param \Exception $error
     * @return string
     */
    public function formatError(\Exception $error): string;
}
