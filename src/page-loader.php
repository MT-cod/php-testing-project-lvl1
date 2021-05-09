<?php

namespace PL;

//Головная функция
function pageLoader(string $url, string $outputDir, string $clientClass = ''): string
{
    $resource = new PL($url, $outputDir);
    $resource->fileProcessing();
    $htmlPath = $resource->getDownloadedHtmlPath();
    return $htmlPath;
}
