<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @covers \Hostnet\Component\CssSniff\Command\SniffCommand
 */
class SniffCommandTest extends TestCase
{
    /**
     * @var SniffCommand
     */
    private $sniff_command;

    protected function setUp(): void
    {
        $this->sniff_command = new SniffCommand();
    }

    public function testExecuteStandardConfig(): void
    {
        $input  = new ArrayInput([]);
        $output = new BufferedOutput();

        $this->sniff_command->run($input, $output);

        self::assertEquals(
            "\n",
            \trim($output->fetch()) . "\n"
        );
    }

    public function testExecuteConsoleOutput(): void
    {
        $input  = new ArrayInput(['files' => [__DIR__ . '/test.less']]);
        $output = new BufferedOutput();

        $this->sniff_command->run($input, $output);

        self::assertEquals(
            \str_replace('{{FILE}}', __DIR__ . '/test.less', \file_get_contents(__DIR__ . '/output.console.txt')),
            \trim($output->fetch()) . "\n"
        );
    }

    public function testExecuteJsonOutput(): void
    {
        $input  = new ArrayInput(['--format' => 'json', 'files' => [__DIR__ . '/test.less']]);
        $output = new BufferedOutput();

        $this->sniff_command->run($input, $output);

        self::assertEquals(
            \str_replace(
                '{{FILE}}',
                \json_encode(__DIR__ . '/test.less'),
                \file_get_contents(__DIR__ . '/output.json.txt')
            ),
            \trim($output->fetch()) . "\n"
        );
    }

    public function testExecuteCheckstyleOutput(): void
    {
        $input  = new ArrayInput(['--format' => 'checkstyle', 'files' => [__DIR__ . '/test.less']]);
        $output = new BufferedOutput();

        $this->sniff_command->run($input, $output);

        self::assertEquals(
            \str_replace('{{FILE}}', __DIR__ . '/test.less', \file_get_contents(__DIR__ . '/output.checkstyle.txt')),
            \trim($output->fetch()) . "\n"
        );
    }

    public function testExecuteEmptyInput(): void
    {
        $input  = new ArrayInput(['--format' => 'json', 'files' => [__DIR__ . '/empty.less']]);
        $output = new BufferedOutput();

        $this->sniff_command->run($input, $output);

        self::assertEquals(
            '{"totals":{"errors":0},"files":[]}' . PHP_EOL,
            $output->fetch()
        );
    }

    public function testExecuteErrorInput(): void
    {
        $input  = new ArrayInput(['--format' => 'json', 'files' => [__DIR__ . '/error.less']]);
        $output = new BufferedOutput();

        $this->sniff_command->run($input, $output);

        self::assertEquals(
            '"unclosed"' . PHP_EOL,
            $output->fetch()
        );
    }

    public function testRunWithEmptyFormatThrowsException(): void
    {
        $input  = new StringInput('--format');
        $output = new NullOutput();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('option requires a value');

        $this->sniff_command->run($input, $output);
    }

    public function testRunWithEmptyStandardThrowsException(): void
    {
        $input  = new StringInput('--standard');
        $output = new NullOutput();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('option requires a value');

        $this->sniff_command->run($input, $output);
    }
}
