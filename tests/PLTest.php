<?php

//Модуль тестирования парсингов файлов в массивы

namespace Downloader\Downloader;

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

    public function testParsingHtml(): void
    {
        $html = (string) file_get_contents($this->outputDir . '/fixtures/test.html');
        $images = ["/assets/professions/php.png"];
        $scripts = ["https://js.stripe.com/v3/", "https://ru.hexlet.io/packs/js/runtime.js"];
        $links = [
            "https://cdn2.hexlet.io/assets/menu.css",
            "/assets/application.css",
            "/courses"
        ];
        $stub = new PL($this->unreachableAddr, $this->outputDir);
        $this->assertEquals($images, $stub->getImages($html));
        $this->assertEquals($scripts, $stub->getScripts($html));
        $this->assertEquals($links, $stub->getLinks($html));
    }

    public function testBlackBoxFuncPLWithNet(): void
    {
        $tryLoad = downloadPage($this->url, $this->outputDir);
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
    }

    public function testConnectionMethods(): void
    {
        $conn1 = new Connection('https://example.com');
        $conn2 = new Connection('bla-bla-bla');
        $conn3 = new Connection($this->unreachableAddr);
        $this->assertTrue($conn1->isUrl());
        $this->assertFalse($conn2->isUrl());
        $this->assertEquals([200, 'OK'], $conn1->getHttpCode());
        $this->assertEquals(0, $conn3->getHttpCode()[0]);
    }

    //Тесты исключений
    public function testExceptionsRetCode0()
    {
        $this->expectExceptionCode(0);
        $test = new PL($this->unreachableAddr, $this->outputDir);
        $test->filesProcessing();
    }
    public function testExceptionsUnreachableAddr()
    {
        $this->expectExceptionMessage(
            "Failed to load $this->unreachableAddr. Returned an error \"Unreachable address\" code \"0\"\n"
        );
        $test = new PL($this->unreachableAddr, $this->outputDir);
        $test->filesProcessing();
    }
    public function testExceptionsWriteData()
    {
        $this->expectExceptionMessage(
            "Failed to write data into \"/testsefewf/\"\n"
        );
        new PL($this->url, '/testsefewf');
    }

    //Подчищаем после тестов
    public function tearDown(): void
    {
        if (file_exists($this->outputDir . '/php-net.html')) {
            unlink($this->outputDir . '/php-net.html');
            $this->recursiveRemoveDir($this->outputDir . '/php-net_files');
        }
    }
    public function recursiveRemoveDir($dir): void
    {
        $includes = glob($dir . '/*');
        foreach ($includes as $include) {
            unlink($include);
        }
        rmdir($dir);
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
