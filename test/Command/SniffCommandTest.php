<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @covers \Hostnet\Component\CssSniff\Command\SniffCommand
 */
class SniffCommandTest extends TestCase
{
    /**
     * @var SniffCommand
     */
    private $sniff_command;

    protected function setUp()
    {
        $this->sniff_command = new SniffCommand();
    }

    public function testExecuteConsoleOutput()
    {
        $input  = new ArrayInput(['file' => __DIR__ . '/test.less']);
        $output = new BufferedOutput();

        $this->sniff_command->run($input, $output);

        self::assertEquals(
            str_replace('{{FILE}}', __DIR__ . '/test.less', file_get_contents(__DIR__ . '/output.console.txt')),
            trim($output->fetch()) . "\n"
        );
    }

    public function testExecuteJsonOutput()
    {
        $input  = new ArrayInput(['--format' => 'json', 'file' => __DIR__ . '/test.less']);
        $output = new BufferedOutput();

        $this->sniff_command->run($input, $output);

        self::assertEquals(
            str_replace(
                '{{FILE}}',
                json_encode(__DIR__ . '/test.less'),
                file_get_contents(__DIR__ . '/output.json.txt')
            ),
            trim($output->fetch()) . "\n"
        );
    }

    public function testExecuteEmptyInput()
    {
        $input  = new ArrayInput(['--format' => 'json', 'file' => __DIR__ . '/empty.less']);
        $output = new BufferedOutput();

        $this->sniff_command->run($input, $output);

        self::assertEquals(
            '{"totals":{"errors":0},"files":[]}' . PHP_EOL,
            $output->fetch()
        );
    }

    public function testExecuteErrorInput()
    {
        $input  = new ArrayInput(['--format' => 'json', 'file' => __DIR__ . '/error.less']);
        $output = new BufferedOutput();

        $this->sniff_command->run($input, $output);

        self::assertEquals(
            '"unclosed"' . PHP_EOL,
            $output->fetch()
        );
    }
}
