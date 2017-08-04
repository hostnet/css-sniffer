<?php

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
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('Cannot find standards file "%s".', $file));
        }

        $standard = new self(basename($file, '.xml'));
        $xml = new \SimpleXMLElement(file_get_contents($file));

        foreach ($xml->xpath('/csssniffer/sniff') as $sniff_data) {
            if (!isset($sniff_data->attributes()['class'])) {
                throw new \LogicException('Missing class attribute for sniff.');
            }

            $class = $sniff_data->attributes()['class']->__toString();
            $args = array_map(function (\SimpleXMLElement $e) {
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
        $this->sniffs[] = $sniff;
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
        return $this->sniffs;
    }
}
