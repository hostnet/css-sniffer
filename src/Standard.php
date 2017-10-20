<?php
declare(strict_types=1);
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff;

class Standard
{
    private $name;
    private $sniffs = [];

    /**
     * Parse a standard xml file into a Standard class.
     *
     * @param string $file
     * @return Standard
     */
    public static function loadFromXmlFile(string $file): self
    {
        if (file_exists($file)) {
            $standard_file = $file;
        } else {
            // Is it a default standard?
            $standard_file = __DIR__ . '/Standard/' . $file . '.xml';

            if (!file_exists($standard_file)) {
                throw new \InvalidArgumentException(sprintf('Cannot find standards file "%s".', $standard_file));
            }
        }

        $standard = new self(preg_replace('/\.xml(\.dist)$/', '', basename($standard_file)));
        $xml      = new \SimpleXMLElement(file_get_contents($standard_file));

        foreach ($xml->xpath('/csssniffer/sniff') as $sniff_data) {
            // Import sniff file?
            if (isset($sniff_data->attributes()['rel'])) {
                $include_file = $sniff_data->attributes()['rel']->__toString();

                if ($include_file[0] === '.') {
                    $include_file = dirname($standard_file) . '/' . $include_file;
                }

                $sub_standard = self::loadFromXmlFile($include_file);

                $standard->extend($sub_standard);
                continue;
            }

            // Defined class?
            if (!isset($sniff_data->attributes()['class'])) {
                throw new \LogicException('Missing class attribute for sniff.');
            }

            $class = $sniff_data->attributes()['class']->__toString();
            $args  = array_map(function (\SimpleXMLElement $e) {
                return $e->__toString();
            }, $sniff_data->xpath('/arg'));

            $standard->addSniff((new \ReflectionClass($class))->newInstanceArgs($args));
        }

        return $standard;
    }

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    private function addSniff(SniffInterface $sniff): void
    {
        $this->sniffs[get_class($sniff)] = $sniff;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return SniffInterface[]
     */
    public function getSniffs(): array
    {
        return array_values($this->sniffs);
    }

    /**
     * Extend the standard with another one.
     *
     * @param Standard $standard
     */
    private function extend(Standard $standard): void
    {
        foreach ($standard->sniffs as $class => $sniff) {
            if (isset($this->sniffs[$class])) {
                continue;
            }

            $this->sniffs[$class] = $sniff;
        }
    }
}
