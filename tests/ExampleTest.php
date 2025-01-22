<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testSomething(): void
    {
        $param = true;
        // $param = false;
        
        $this->assertTrue($param);
    }
}