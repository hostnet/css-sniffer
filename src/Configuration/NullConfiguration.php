<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Configuration;

final class NullConfiguration implements SnifferConfigurationInterface
{
    public function getFiles(): array
    {
        return [];
    }
}
