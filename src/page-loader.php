<?php

namespace PL;

//Головная функция
function page_loader(string $url, string $outputDir, string $clientClass = ''): string
{
    $html = getHtml($url);
    $outputFileName = genName($url);
    $outPath = (mb_substr($outputDir, -1, 1) !== '/') ? $outputDir . '/' : $outputDir;
    $outputFileNameWithPath = $outPath . $outputFileName;
    if (file_put_contents($outputFileNameWithPath, $html) !== false) {
        return $outputFileNameWithPath;
    }
    return 'Error with writing a file!';
}
function getHtml($url)
{
    $client = new \GuzzleHttp\Client();
    return $client->get($url)->getBody()->getContents();
}
function genName(string $url): string
{
    $hostParts = explode('.', parse_url($url, PHP_URL_HOST));
    $pathParts = explode('/', parse_url($url, PHP_URL_PATH));
    return implode('-', $hostParts) . implode('-', $pathParts) . '.html';
}
