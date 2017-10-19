<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Configuration;

use Hostnet\Component\CssSniff\File;
use Yannickl88\Component\CSS\Tokenizer;

final class SingleFileConfiguration implements SnifferConfigurationInterface
{
    private $file_name;

    public function __construct(string $file_name)
    {
        $this->file_name = $file_name;
    }

    public function getFile(): File
    {
        return new File($this->file_name, (new Tokenizer())->tokenize(file_get_contents($this->file_name)));
    }
}
