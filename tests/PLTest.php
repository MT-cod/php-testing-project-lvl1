<?php

//Модуль тестирования парсингов файлов в массивы

namespace PL;

use PHPUnit\Framework\TestCase;

class PLTest extends TestCase
{
    private string $outputDir;
    private string $url;
    private string $unreachableAddr;

    public function setUp(): void
    {
        $this->outputDir = __DIR__;
        $this->url = 'https://php.net';
        $this->unreachableAddr = 'http://testtesttt.test';
    }
    /*public function testFuncPLWithNet(): void
    {
        $tryLoad = pageLoader($this->url, $this->outputDir);
        $this->assertEquals($this->outputDir . '/php-net.html', $tryLoad);

        $this->assertFileExists($this->outputDir . '/php-net_files');
        $this->assertFileExists(
            $this->outputDir .
            '/php-net_files/' .
            'php-net--ajax.googleapis.com-ajax-libs-jquery-1.10.2-jquery.min.js'
        );
        $this->assertFileExists(
            $this->outputDir .
            '/php-net_files/' .
            'php-net-images-to-top@2x.png'
        );
        $this->assertFileExists(
            $this->outputDir .
            '/php-net_files/' .
            'www-php-net-index.php'
        );
        $this->assertTrue(str_contains(
            file_get_contents($this->outputDir . '/php-net.html'),
            'php-net_files/' .
            'php-net--ajax.googleapis.com-ajax-libs-jquery-1.10.2-jquery.min.js'
        ));
    }*/

    public function testExceptions()
    {
        $this->expectExceptionMessage(
            'Error: Connection to http://testtesttt.test returned an error [0] code 0'
        );
        pageLoader($this->unreachableAddr, $this->outputDir);
    }

    public function tearDown(): void
    {
        unlink($this->outputDir . '/php-net.html');
        $this->recursiveRemoveDir($this->outputDir . '/php-net_files');
    }
    public function recursiveRemoveDir($dir): void
    {
        $includes = glob($dir . '/*');
        foreach ($includes as $include) {
            unlink($include);
        }
        rmdir($dir);
    }

    /*public function testFuncPLWithoutNet(): void
    {
        $stub = $this->createMock(\GuzzleHttp\Client::class);
        $stub->method('get')
            ->method('getBody')
            ->method('getContents')
            ->willReturn('bla-bla-bla');
        $this->assertSame(
        $this->outputDir .
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
