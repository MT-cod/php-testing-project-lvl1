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
        fwrite(STDERR, "Error: {$e->getMessage()} code {$e->getCode()}\n");
        /*exit($e->getCode());*/
    }
    return null;
}
