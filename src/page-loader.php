<?php

namespace PL;

//Головная функция
function pageLoader(string $url, string $outputDir, string $clientClass = ''): string | null
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
