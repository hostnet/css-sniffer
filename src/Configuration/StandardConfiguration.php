<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Configuration;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\Standard;
use Yannickl88\Component\CSS\Tokenizer;

final class StandardConfiguration implements SnifferConfigurationInterface
{
    private $standard;

    public function __construct(Standard $standard)
    {
        $this->standard = $standard;
    }

    public function getFiles(): array
    {
        $files     = [];
        $tokenizer = new Tokenizer();

        foreach ($this->listFile() as $file) {
            // Have we not yet seen it and should we sniff it?
            if (isset($files[$file]) || !$this->shouldSniff($file)) {
                continue;
            }

            $files[$file] = new File($file, $tokenizer->tokenize(file_get_contents($file)));
        }

        return array_values($files);
    }

    private function listFile(): \Generator
    {
        // list all files in the directories
        foreach ($this->standard->getDirectories() as $directory) {
            if (!is_dir($directory)) {
                throw new \LogicException(sprintf('Directory "%s" is not a directory.', $directory));
            }

            /* @var $child \SplFileInfo */
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $child) {
                $name = $child->__toString();

                if (!in_array($child->getExtension(), ['css', 'less'], true)) {
                    continue;
                }

                yield realpath($name);
            }
        }

        // list all files
        foreach ($this->standard->getFiles() as $file) {
            if (!is_file($file)) {
                throw new \LogicException(sprintf('File "%s" is not a file.', $file));
            }

            yield realpath($file);
        }
    }

    private function shouldSniff(string $file): bool
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            $file = str_replace('\\', '/', $file);
        }

        foreach ($this->standard->getExclusionPatterns() as $pattern) {
            if (1 === preg_match($pattern, $file)) {
                return false;
            }
        }

        return true;
    }
}
