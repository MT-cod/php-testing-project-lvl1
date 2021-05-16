<?php

namespace Downloader\Downloader;

//Головная функция
function downloadPage(string $url, string $outputDir, string $clientClass = ''): string
{
    try {
        $resource = new PL($url, $outputDir);
        $resource->filesProcessing();
        return $resource->getDownloadedHtmlPath();
    } catch (\Exception $e) {
        fwrite(STDERR, $e->getMessage());
        exit($e->getCode());
    }
}
