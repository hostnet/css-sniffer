<?php
declare(strict_types=1);
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff;

class Standard
{
    private $name;
    private $files              = [];
    private $directories        = [];
    private $exclusion_patterns = [];

    /**
     * @var SniffConfiguration[]
     */
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
                throw new \InvalidArgumentException(sprintf('Cannot find standards file "%s".', $file));
            }
        }

        $standard = new self(preg_replace('/\.xml(\.dist)$/', '', basename($standard_file)));
        $data     = new \SimpleXMLElement(file_get_contents($standard_file));

        // <file> elements
        foreach ($data->file as $include_file) {
            $include_file = $include_file->__toString();

            if ($include_file[0] === '.') {
                $include_file = dirname($standard_file) . '/' . $include_file;
            }

            $standard->files[] = $include_file;
        }

        // <directory> elements
        foreach ($data->directory as $include_directory) {
            $include_directory = $include_directory->__toString();

            if ($include_directory[0] === '.') {
                $include_directory = dirname($standard_file) . '/' . $include_directory;
            }

            $standard->directories[] = $include_directory;
        }

        // <exclude-pattern> elements
        foreach ($data->{'exclude-pattern'} as $pattern) {
            $standard->exclusion_patterns[] = self::makeRegex($pattern->__toString());
        }

        // <sniff> elements
        foreach ($data->sniff as $sniff_data) {
            // <sniff rel="..." /> element?
            if (isset($sniff_data->attributes()->rel)) {
                $include_file = $sniff_data->attributes()->rel->__toString();

                if ($include_file[0] === '.') {
                    $include_file = dirname($standard_file) . '/' . $include_file;
                }

                $standard->extend(self::loadFromXmlFile($include_file));

                continue;
            }

            // <sniff class="..." /> element?
            if (isset($sniff_data->attributes()->class)) {
                $class              = $sniff_data->attributes()->class->__toString();
                $args               = [];
                $exclusion_patterns = [];

                // <arg> elements
                foreach ($sniff_data->{'arg'} as $pattern) {
                    $args[] = $pattern->__toString();
                }

                // <exclude-pattern> elements
                foreach ($sniff_data->{'exclude-pattern'} as $pattern) {
                    $exclusion_patterns[] = self::makeRegex($pattern->__toString());
                }

                $sniff = new SniffConfiguration(
                    (new \ReflectionClass($class))->newInstanceArgs($args),
                    $exclusion_patterns
                );

                if (isset($standard->sniffs[$class])) {
                    $standard->sniffs[$class]->extend($sniff);
                } else {
                    $standard->sniffs[$class] = $sniff;
                }

                continue;
            }
            throw new \LogicException('Missing class attribute for sniff.');
        }

        return $standard;
    }

    private static function makeRegex(string $pattern): string
    {
        return '`' . implode('.*', array_map(function (string $part) {
            return preg_quote($part, '`');
        }, explode('*', $pattern))) . '`i';
    }

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Return the sniff name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Return all directories to sniff.
     *
     * @return string[]
     */
    public function getDirectories(): array
    {
        return $this->directories;
    }

    /**
     * Return all files to sniff.
     *
     * @return string[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Return all exclusion patterns.
     *
     * @return string[]
     */
    public function getExclusionPatterns(): array
    {
        return $this->exclusion_patterns;
    }

    /**
     * Return all sniffs which are contained by this Standard.
     *
     * @return SniffConfiguration[]
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
                $this->sniffs[$class]->extend($sniff);
            } else {
                $this->sniffs[$class] = $sniff;
            }
        }
    }
}
