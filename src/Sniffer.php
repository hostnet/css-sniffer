<?php
declare(strict_types=1);
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff;

/**
 * Sniffer class which allows for adding sniffs and processing files.
 */
final class Sniffer
{
    /**
     * @var SniffInterface[][]
     */
    private $listeners = [];

    /**
     * Add a sniff.
     *
     * @param SniffInterface $s
     */
    public function addSniff(SniffInterface $s): void
    {
        foreach ($s->register() as $type) {
            if (!isset($this->listeners[$type])) {
                $this->listeners[$type] = [];
            }

            $this->listeners[$type][] = $s;
        }
    }

    public function loadStandard(Standard $standard): void
    {
        foreach ($standard->getSniffs() as $sniff) {
            $this->addSniff($sniff);
        }
    }

    /**
     * Process a file with all the registered sniffs. This will add violations
     * if there are any.
     *
     * @param File $file
     */
    public function process(File $file): void
    {
        $tokens = $file->getTokens();

        for ($i = 0, $n = count($tokens); $i < $n; $i++) {
            if (isset($this->listeners[$tokens[$i]->type])) {
                foreach ($this->listeners[$tokens[$i]->type] as $sniff) {
                    $sniff->process($file, $i);
                }
            }
        }
    }
}
