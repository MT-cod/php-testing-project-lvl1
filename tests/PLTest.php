<?php

//Модуль тестирования парсингов файлов в массивы

namespace PL;

use PHPUnit\Framework\TestCase;

class PLTest extends TestCase
{
    private static string $outputDir = __DIR__;

    /*public function testFuncPLWithoutNet(): void
    {
        $stub = $this->createMock(\GuzzleHttp\Client::class);
        $stub->method('get')
            ->method('getBody')
            ->method('getContents')
            ->willReturn('bla-bla-bla');
        $this->assertSame(
        static::$outputDir .
         '/ru-hexlet-io-courses.html', page_loader('https://ru.hexlet.io/courses')
        );
    }*/
    public function testFuncPLWithNet(): void
    {
        $tryLoad = page_loader('https://ru.hexlet.io/courses', static::$outputDir);
        $this->assertEquals(static::$outputDir . '/ru-hexlet-io-courses.html', $tryLoad);
    }
    /*public function testBlackBox(): void
    {
        $expRes = "\nPage was successfully downloaded into " .
            getcwd() .
            "/bin/ru-hexlet-io-courses.html\n";
        $tryLoad = shell_exec(getcwd() . '/bin/page-loader https://ru.hexlet.io/courses');
        $this->assertEquals($expRes, $tryLoad);
    }*/
}
