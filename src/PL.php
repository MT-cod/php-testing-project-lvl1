<?php

namespace Downloader\Downloader;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class PL
{
    public string $url;
    public string $htmlAsStr;
    public string $outputName;
    public string $outPath;
    public string $outputNameWithPath;
    public Logger $logger;

    public function __construct(string $url, string $outputDir)
    {
        $this->outPath = (str_ends_with($outputDir, '/')) ? $outputDir : $outputDir . '/';
        $this->testWriteFile($url, $this->outPath);
        $this->logger = new Logger('pl_logger');
        $this->logger->pushHandler(new StreamHandler($this->outPath . 'page-loader.log', Logger::DEBUG));
        $this->logger->info('Start pageloading');
        $this->url = $url;
    }

    public function filesProcessing(): void
    {
        $this->getHtmlData();
        $this->outputName = $this->genSlugName($this->url);
        $this->outputNameWithPath = $this->outPath . $this->outputName;
        $htmlAsStrWithImgs = $this->deepFilePsng($this->getImages($this->htmlAsStr), $this->htmlAsStr);
        $htmlAsStrWithImgsAndScrs = $this->deepFilePsng($this->getScripts($htmlAsStrWithImgs), $htmlAsStrWithImgs);
        $htmlAsStrWithImgsAndScrsAndlinks = $this->deepFilePsng(
            $this->getLinks($htmlAsStrWithImgsAndScrs),
            $htmlAsStrWithImgsAndScrs
        );
        $this->writeHtml($htmlAsStrWithImgsAndScrsAndlinks);
    }
    public function deepFilePsng(array $files, string $htmlAsStr): string
    {
        if ($files != []) {
            //Создаём каталог для хранения файлов, если не создан раньше
            if (!file_exists($this->outputNameWithPath . '_files')) {
                if (!@mkdir($this->outputNameWithPath . '_files')) {
                    $this->logger->error(
                        "Failed to create dir \"$this->outputNameWithPath" . "_files\""
                    );
                    fwrite(
                        STDERR,
                        "Failed to create dir \"$this->outputNameWithPath" . "_files\"\n"
                    );
                }
            }
            $resultHtmlAsStr = $htmlAsStr;
            array_walk($files, function ($file) use (&$resultHtmlAsStr): void {
                if (str_starts_with($file, '/')) {
                    //Если путь до файлов в html указан относительно
                    $newFileName = $this->genSlugName($this->url . $file);
                    $fileRoot = $this->url . $file;
                    $resultHtmlAsStr = $this->writeFile($file, $newFileName, $fileRoot, $resultHtmlAsStr);
                } else {
                    //Если путь до файлов в html указан с url
                    if ($this->checkUrlInHost($file)) {
                        //Если файл в нашем домене, то берём его
                        $newFileName = $this->genSlugName($file);
                        $fileRoot = $file;
                        $resultHtmlAsStr = $this->writeFile($file, $newFileName, $fileRoot, $resultHtmlAsStr);
                    }
                }
            });
            return $resultHtmlAsStr;
        }
        return $htmlAsStr;
    }

    public function getHtmlData(): void
    {
        $conn = new Connection($this->url);
        if ($conn->isUrl() === false) {
            $this->logger->error('Url incorrect!');
            throw new \Exception("Url incorrect!\n", 1);
        }
        $connHttpCode = $conn->getHttpCode();
        $htmlAsStr = @file_get_contents($this->url);
        if ($htmlAsStr === false || $htmlAsStr === '') {
            $this->logger->error(
                "Failed to load $this->url. Returned an error \"$connHttpCode[1]\" code \"$connHttpCode[0]\""
            );
            throw new \Exception(
                "Failed to load $this->url. Returned an error \"$connHttpCode[1]\" code \"$connHttpCode[0]\"\n",
                $connHttpCode[0]
            );
        }
        $this->htmlAsStr = $htmlAsStr;
    }

    public function testWriteFile(string $url, string $outputDir): void
    {
        $testWriteFile = @fopen($outputDir . 'test', 'w');
        if ($testWriteFile === false) {
            throw new \Exception(
                "Failed to write data into \"$outputDir\"\n",
                1
            );
        }
        unlink($outputDir . 'test');
    }
    public function writeHtml(string $htmlAsStr): void
    {
        $putRes = @file_put_contents($this->outputNameWithPath . '.html', $htmlAsStr);
        if ($putRes === false) {
            $this->logger->error("Failed to write \"$this->outputNameWithPath.html\"");
            throw new \Exception(
                "Failed to write \"$this->outputNameWithPath.html\"\n",
                1
            );
        }
    }
    public function writeFile(string $file, string $newFileName, string $fileRoot, string $htmlAsStr): string
    {
        $newFileNameWithDir = $this->outputName . '_files/' . $newFileName;
        $newFileNameWithRoot = $this->outputNameWithPath . '_files/' . $newFileName;
        $conn = new Connection($fileRoot);
        $connHttpCode = $conn->getHttpCode();
        $putRes = @file_put_contents($newFileNameWithRoot, file($fileRoot));
        if ($putRes === false) {
            $this->logger->error(
                "Failed to write \"$newFileNameWithRoot\".
 Returned an error \"$connHttpCode[1]\" code \"$connHttpCode[0]\""
            );
            fwrite(
                STDERR,
                "Failed to write \"$newFileNameWithRoot\".
 Returned an error \"$connHttpCode[1]\" code \"$connHttpCode[0]\"\n"
            );
        } else {
            $this->logger->info("Write $newFileNameWithRoot. Size [$putRes]");
        }
        return str_replace($file, $newFileNameWithDir, $htmlAsStr);
    }

    public function getDownloadedHtmlPath(): string
    {
        return $this->outputNameWithPath . '.html';
    }

    public function getImages(string $htmlAsStr): array
    {
        $imgSearch = preg_match_all('/(?<=")[^"]+\.(png|jpg)(?=")/', $htmlAsStr, $images);
        return ($imgSearch > 0) ? $images[0] : [];
    }
    public function getScripts(string $htmlAsStr): array
    {
        $scrSearch = preg_match_all('/(?<=<script src=")[^"]+(?=")/', $htmlAsStr, $scripts);
        return ($scrSearch > 0) ? $scripts[0] : [];
    }
    public function getLinks(string $htmlAsStr): array
    {
        $linkSearch = preg_match_all('/(?<=<link).+((?<=href=")[^"]+)(?=")/', $htmlAsStr, $links);
        return ($linkSearch > 0) ? $links[1] : [];
    }

    public function genSlugName(string $url): string
    {
        $host = (string) parse_url($url, PHP_URL_HOST);
        $path = (string) parse_url($url, PHP_URL_PATH);
        $hostParts = explode('.', $host);
        $pathParts = explode('/', $path);
        return implode('-', $hostParts) . implode('-', $pathParts);
    }

    public function checkUrlInHost(string $urlOfFile): bool
    {
        $hostOfFile = parse_url($urlOfFile, PHP_URL_HOST);
        $host = parse_url($this->url, PHP_URL_HOST);
        return ($hostOfFile === $host || $hostOfFile === ('www.' . $host));
    }
}
