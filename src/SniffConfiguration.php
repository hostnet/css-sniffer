<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff;

/**
 * Wrapper class for a configured sniff from a standard.
 *
 * @internal
 */
final class SniffConfiguration
{
    private $sniff;
    private $exclusion_patterns;

    public function __construct(SniffInterface $sniff, iterable $exclusion_patterns)
    {
        $this->sniff              = $sniff;
        $this->exclusion_patterns = $exclusion_patterns;
    }

    public function getSniff(): SniffInterface
    {
        return $this->sniff;
    }

    public function shouldSniff(File $file): bool
    {
        $file_name = $file->getName();

        if (DIRECTORY_SEPARATOR === '\\') {
            $file_name = str_replace('\\', '/', $file_name);
        }

        foreach ($this->exclusion_patterns as $pattern) {
            if (1 === preg_match($pattern, $file_name)) {
                return false;
            }
        }

        return true;
    }

    public function extend(SniffConfiguration $configuration): void
    {
        if (get_class($configuration->sniff) !== get_class($this->sniff)) {
            throw new \LogicException(sprintf(
                'Cannot merge SniffConfiguration which have different sniffs. Got "%s", expected "%s".',
                get_class($configuration->sniff),
                get_class($this->sniff)
            ));
        }

        $this->sniff              = $configuration->sniff;
        $this->exclusion_patterns = array_merge($this->exclusion_patterns, $configuration->exclusion_patterns);
    }
}
