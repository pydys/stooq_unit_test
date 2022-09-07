<?php declare(strict_types=1);

namespace Tests\App;

use PHPUnit\Framework\TestCase;


class ExampleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @coversNothing
     */
    public function testAssertTrueSuccess()
    {
        $this->assertTrue(true);
    }

}
