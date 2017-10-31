<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\SniffConfiguration;
use Hostnet\Component\CssSniff\SniffInterface;
use Hostnet\Component\CssSniff\Standard;

class SingleStandard
{
    public static function load(string $sniff): Standard
    {
        $file = tempnam(sys_get_temp_dir(), 'test_standard_');

        file_put_contents($file, sprintf('<csssniffer><sniff class="%s" /></csssniffer>', $sniff));

        try {
            $standard = Standard::loadFromXmlFile($file);
        } finally {
            unlink($file);
        }

        return $standard;
    }
}
