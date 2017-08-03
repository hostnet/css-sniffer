<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);
namespace Hostnet\Component\CssSniff\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
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

    protected function setUp()
    {
        $this->sniff_command = new SniffCommand();
    }

    public function testExecuteConsoleOutput()
    {
        $input  = new ArrayInput(['file' => __DIR__ . '/test.less']);
        $output = new BufferedOutput();

        $this->sniff_command->run($input, $output);

        self::assertEquals("Colors should always be 6 characters hex values. At line 2:12.\n", $output->fetch());
    }

    public function testExecuteJsonOutput()
    {
        $input  = new ArrayInput(['--format' => 'json', 'file' => __DIR__ . '/test.less']);
        $output = new BufferedOutput();

        $this->sniff_command->run($input, $output);

        self::assertEquals(
            "[{\"msg\":\"Colors should always be 6 characters hex values.\",\"line\":2,\"start\":12,\"end\":16}]\n",
            $output->fetch()
        );
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Empty input.
     */
    public function testExecuteEmptyInput()
    {
        $input  = new ArrayInput(['file' => __DIR__ . '/empty.less']);
        $output = new NullOutput();

        $this->sniff_command->run($input, $output);
    }
}
