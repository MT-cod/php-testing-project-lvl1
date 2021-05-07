<?php

//Модуль тестирования парсингов файлов в массивы

namespace PL;

use PHPUnit\Framework\TestCase;

class PLTest extends TestCase
{
    private static string $outputDir = __DIR__;

    public function testFuncPLWithNet(): void
    {
        $tryLoad = pageLoader('https://ru.hexlet.io/courses', static::$outputDir);
        $this->assertEquals(static::$outputDir . '/ru-hexlet-io-courses.html', $tryLoad);

        $this->assertFileExists(static::$outputDir . '/ru-hexlet-io-courses_files');
        $this->assertFileExists(
            static::$outputDir .
            '/ru-hexlet-io-courses_files/' .
            'cdn2-hexlet-io-assets-' .
            'hexlet_logo-e99fc2b3b7c1eec88899f3af1435a39aaac6fd29d011dfe2342499c0884b7a96.png'
        );
        $this->assertTrue(
            str_contains(
            file_get_contents(static::$outputDir . '/ru-hexlet-io-courses.html'),
            static::$outputDir .
            '/ru-hexlet-io-courses_files/' .
            'cdn2-hexlet-io-assets-' .
            'hexlet_logo-e99fc2b3b7c1eec88899f3af1435a39aaac6fd29d011dfe2342499c0884b7a96.png'
            )
        );
    }

    /*public function testFuncPLWithoutNet(): void
    {
        $stub = $this->createMock(\GuzzleHttp\Client::class);
        $stub->method('get')
            ->method('getBody')
            ->method('getContents')
            ->willReturn('bla-bla-bla');
        $this->assertSame(
        static::$outputDir .
         '/ru-hexlet-io-courses.html', pageLoader('https://ru.hexlet.io/courses')
        );
    }*/
    /*public function testBlackBox(): void
    {
        $expRes = "\nPage was successfully downloaded into " .
            getcwd() .
            "/bin/ru-hexlet-io-courses.html\n";
        $tryLoad = shell_exec(getcwd() . '/bin/page-loader https://ru.hexlet.io/courses');
        $this->assertEquals($expRes, $tryLoad);
    }*/
}
