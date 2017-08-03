<?php
declare(strict_types=1);
/**
 * @copyright 2015-2017 Hostnet B.V.
 */
namespace Hostnet\Component\CssSniff\Command;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\Output\ConsoleFormatter;
use Hostnet\Component\CssSniff\Output\FormatterInterface;
use Hostnet\Component\CssSniff\Output\JsonFormatter;
use Hostnet\Component\CssSniff\Sniff\ClassSniff;
use Hostnet\Component\CssSniff\Sniff\ColorSniff;
use Hostnet\Component\CssSniff\Sniff\EmptySniff;
use Hostnet\Component\CssSniff\Sniff\IdSniff;
use Hostnet\Component\CssSniff\Sniff\VariableSniff;
use Hostnet\Component\CssSniff\Sniffer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Yannickl88\Component\CSS\Tokenizer;

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
            ->addOption('pretty', 'p', InputOption::VALUE_NONE, 'Pretty format output')
            ->addArgument('file', null, 'Input file')
            ->setDescription('Sniffs the given input file and returns the result.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasArgument('file') && !empty($input->getArgument('file'))) {
            $contents = file_get_contents($input->getArgument('file'));
        } elseif (0 === ftell(STDIN)) {
            $contents = '';
            while (!feof(STDIN)) {
                $contents .= fread(STDIN, 1024);
            }
        } else {
            $contents = '';
        }

        if (empty($contents)) {
            throw new \RuntimeException('Empty input.');
        }

        $file = new File((new Tokenizer())->tokenize($contents));

        $sniffer = new Sniffer();
        $sniffer->addSniff(new ClassSniff());
        $sniffer->addSniff(new IdSniff());
        $sniffer->addSniff(new VariableSniff());
        $sniffer->addSniff(new ColorSniff());
        $sniffer->addSniff(new EmptySniff());
        $sniffer->process($file);

        $output->writeln($this->getFormatter($input->getOption('format'))->format(
            $file->getViolations(),
            $input->getOption('pretty')
        ));

        return $file->isOk() ? 0 : 1;
    }

    private function getFormatter(string $type): FormatterInterface
    {
        switch ($type) {
            case 'json':
                return new JsonFormatter();
        }

        return new ConsoleFormatter();
    }
}
