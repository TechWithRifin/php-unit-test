<?php

namespace Php\Tests;

use PHPUnit\Framework\TestCase;

class CounterStaticTest extends TestCase
{

    public static Counter $counter;

    public static function setUpBeforeClass(): void //method ini hanya akan dijalankan sekali saja pada saat awal sebelum semua method unit test di dalam class ini di eksekusi
    {
        self::$counter = new Counter();
        echo "set up before class" . PHP_EOL;
    }

    public function testFirst()
    {
        self::$counter->increment();
        self::assertEquals(1, self::$counter->getCounter());
    }

    public function testSecond()
    {
        self::$counter->increment();
        self::assertEquals(2, self::$counter->getCounter()); //karena object counter tidak direset setiap kali method unit test dieksekusi maka nilai counter akan bertambah menjadi 2
    }

    public static function tearDownAfterClass(): void
    {
        echo PHP_EOL . "semua method unit test telah selesai dieksekusi" . PHP_EOL;
    }
}