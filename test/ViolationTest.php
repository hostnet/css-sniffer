<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\CssSniff\Violation
 */
class ViolationTest extends TestCase
{
    public function testGeneric()
    {
        $violation = new Violation('phpunit', 'foobar', 1, 2, 3);

        self::assertSame('foobar', $violation->getMsg());
        self::assertSame(1, $violation->getLine());
        self::assertSame(2, $violation->getStart());
        self::assertSame(3, $violation->getEnd());
    }
}
