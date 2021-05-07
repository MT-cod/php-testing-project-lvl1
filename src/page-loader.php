<?php

namespace PL;

//Головная функция
function pageLoader(string $url, string $outputDir, string $clientClass = ''): string
{
    $htmlPath = fileProcessing($url, $outputDir);
    return $htmlPath;
}

function fileProcessing(string $url, string $outputDir): string
{
    $htmlAsStr = file_get_contents($url);
    $outputName = genSlugName($url);
    $outPath = (mb_substr($outputDir, -1, 1) !== '/') ? $outputDir . '/' : $outputDir;
    $outputNameWithPath = $outPath . $outputName;
//Обрабатываем наличие картинок
    $images = getImg($htmlAsStr);
    if ($images != []) {
        if (!file_exists($outputNameWithPath . '_files')) {
            mkdir($outputNameWithPath . '_files');
        }
        foreach ($images as $img) {
            $newImgName = genSlugName($img);
            $newImgNameWithPath = $outputNameWithPath . '_files/' . $newImgName;
            if (file_put_contents($newImgNameWithPath, file($img)) === false) {
                return '[Error writing file!]';
            }
            $htmlAsStr = str_replace($img, $newImgNameWithPath, $htmlAsStr);
        }
    }
//Загружаем и пишем html
    if (file_put_contents($outputNameWithPath . '.html', $htmlAsStr) !== false) {
        return $outputNameWithPath . '.html';
    }
    return '[Error writing file!]';
}
function getImg(string $htmlAsStr): array
{
    $imgSearch = preg_match_all('/(?<=")[^"]+\.(png|jpg)(?=")/', $htmlAsStr, $images);
    return ($imgSearch > 0) ? $images[0] : [];
}
function genSlugName(string $url): string
{
    $hostParts = explode('.', parse_url($url, PHP_URL_HOST));
    $pathParts = explode('/', parse_url($url, PHP_URL_PATH));
    return implode('-', $hostParts) . implode('-', $pathParts);
}
