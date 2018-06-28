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
    public static function load(string $sniff, array $args = []): Standard
    {
        $file = tempnam(sys_get_temp_dir(), 'test_standard_');

        $sniff_args = array_reduce($args, function (string $carry, string $arg) {
            return $carry . '<arg>' . $arg . '</arg>';
        }, '');

        file_put_contents($file, sprintf('<csssniffer><sniff class="%s">%s</sniff></csssniffer>', $sniff, $sniff_args));

        try {
            $standard = Standard::loadFromXmlFile($file);
        } finally {
            unlink($file);
        }

        return $standard;
    }
}
