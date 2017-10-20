<?php
declare(strict_types=1);
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff\Command;

use Hostnet\Component\CssSniff\Configuration\CliConfiguration;
use Hostnet\Component\CssSniff\Configuration\NullConfiguration;
use Hostnet\Component\CssSniff\Configuration\StdinConfiguration;
use Hostnet\Component\CssSniff\Output\CheckstyleFormatter;
use Hostnet\Component\CssSniff\Output\ConsoleFormatter;
use Hostnet\Component\CssSniff\Output\FormatterInterface;
use Hostnet\Component\CssSniff\Output\JsonFormatter;
use Hostnet\Component\CssSniff\Sniffer;
use Hostnet\Component\CssSniff\Standard;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SniffCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('sniff')
            ->addOption(
                'format',
                null,
                InputOption::VALUE_OPTIONAL,
                'Type of output format, default: console',
                'console'
            )
            ->addOption(
                'standard',
                's',
                InputOption::VALUE_OPTIONAL,
                'Code Standard to use, by default the Hostnet standard is used. This is the path to the xml file.'
            )
            ->addOption('pretty', 'p', InputOption::VALUE_NONE, 'Pretty format output')
            ->addOption(
                'no-exit-code',
                null,
                InputOption::VALUE_NONE,
                'Always return 0 as exit code, regardless of the result'
            )
            ->addArgument('files', InputArgument::IS_ARRAY, 'Input file')
            ->setDescription('Sniffs the given input file and returns the result.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasArgument('files') && !empty($input->getArgument('files'))) {
            $config = new CliConfiguration($input->getArgument('files'));
        } elseif (0 === ftell(STDIN)) {
            $config = new StdinConfiguration();
        } else {
            $config = new NullConfiguration();
        }

        $formatter = $this->getFormatter($input->getOption('format'), $input->getOption('pretty'));

        try {
            $files = $config->getFiles();
        } catch (\RuntimeException $e) {
            $output->writeln($formatter->formatError($e));
            return 0;
        }

        $standard = Standard::loadFromXmlFile($input->getOption('standard') ?? __DIR__ . '/../Standard/Hostnet.xml');

        $sniffer = new Sniffer();
        $sniffer->loadStandard($standard);
        $ok = $sniffer->process($files);

        $output->writeln($formatter->format($files));

        return $input->getOption('no-exit-code') || $ok ? 0 : 1;
    }

    private function getFormatter(string $type, bool $pretty_format): FormatterInterface
    {
        switch ($type) {
            case 'json':
                return new JsonFormatter($pretty_format);
            case 'checkstyle':
                return new CheckstyleFormatter($pretty_format);
        }

        return new ConsoleFormatter($pretty_format);
    }
}
