<?php

namespace PL;

//Головная функция
function pageLoader(string $url, string $outputDir, string $clientClass = ''): string
{
    try {
        $resource = new PL($url, $outputDir);
    } catch (\Exception $e) {
        fwrite(STDERR, "Error: {$e->getMessage()} code {$e->getCode()}");
        exit($e->getCode());
    }
    $resource->filesProcessing();
    return $resource->getDownloadedHtmlPath();
}
