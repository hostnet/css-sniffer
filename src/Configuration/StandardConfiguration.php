<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Configuration;

use Hostnet\Component\CssSniff\Standard;

final class StandardConfiguration implements SnifferConfigurationInterface
{
    private $standard;

    public function __construct(Standard $standard)
    {
        $this->standard = $standard;
    }

    public function getFiles(): array
    {
        return [];
    }
}
