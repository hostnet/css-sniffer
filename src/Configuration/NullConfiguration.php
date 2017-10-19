<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Configuration;

use Hostnet\Component\CssSniff\File;

final class NullConfiguration implements SnifferConfigurationInterface
{
    public function getFile(): File
    {
        return new File('', []);
    }
}
