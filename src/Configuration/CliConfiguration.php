<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Configuration;

use Hostnet\Component\CssSniff\File;
use Yannickl88\Component\CSS\Tokenizer;

final class CliConfiguration implements SnifferConfigurationInterface
{
    private $file_names;

    public function __construct(array $file_names)
    {
        $this->file_names = $file_names;
    }

    public function getFiles(): array
    {
        $files     = [];
        $tokenizer = new Tokenizer();

        foreach ($this->file_names as $file) {
            if (is_dir($file)) {
                /* @var $child \SplFileInfo */
                foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($file)) as $child) {
                    $name = $child->__toString();

                    if (isset($files[$name])
                        || !in_array($child->getExtension(), ['css', 'less'], true)
                    ) {
                        continue;
                    }

                    $files[$name] = new File($name, $tokenizer->tokenize(file_get_contents($name)));
                }
            } elseif (!isset($files[$file])) {
                $files[$file] = new File($file, $tokenizer->tokenize(file_get_contents($file)));
            }
        }

        return array_values($files);
    }
}
