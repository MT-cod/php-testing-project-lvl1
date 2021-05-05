<?php

//Модуль тестирования парсингов файлов в массивы

namespace PL;

use PHPUnit\Framework\TestCase;

class PLTest extends TestCase
{
    private static string $outputDir = '/home/mamont/Projects/tmp';

    public function testLoadingOnly(): void
    {
        $testLoadingResult = page_loader('https://ru.hexlet.io/courses', static::$outputDir);
        $this->assertEquals(static::$outputDir . '/ru-hexlet-io-courses.html', $testLoadingResult);
    }
    /*public function testBlackBox(): void
    {
        $testLoadingResult = page_loader('https://ru.hexlet.io/courses');
        $this->assertEquals(static::$testReturn, $testLoadingResult);
        $this->ParseJsonTestResult = getAssocArrayFromFile(__DIR__ . '/fixtures/file1.json');
        $this->expectOutputString(static::$parseFileRightResult);
        print_r($this->ParseJsonTestResult);
    }*/
}
