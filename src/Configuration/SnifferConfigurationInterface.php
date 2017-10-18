<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Configuration;

use Hostnet\Component\CssSniff\File;

/**
 * Implementations of this interface provide a way to configure the sniffer and
 * provide file(s) to process.
 */
interface SnifferConfigurationInterface
{
    public function getFile(): File;
}
