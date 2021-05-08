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
    $outPath = (str_ends_with($outputDir, '/')) ? $outputDir : $outputDir . '/';
    $outputNameWithPath = $outPath . $outputName;
//Обрабатываем наличие картинок
    $images = getImg($htmlAsStr);
    if ($images != []) {
        if (!file_exists($outputNameWithPath . '_files')) {
            mkdir($outputNameWithPath . '_files');
        }
        foreach ($images as $img) {
            if (str_starts_with($img, '/')) {
            //Если путь до картинок в html указан относительно
                $newImgName = genSlugName($url . $img);
                $imgRoot = $url . $img;
            } else {
            //Если путь до картинок в html указан с url
                $newImgName = genSlugName($img);
                $imgRoot = $img;
            }

            $newImgNameWithDir = $outputName . '_files/' . $newImgName;
            $newImgNameWithRoot = $outputNameWithPath . '_files/' . $newImgName;
            if (file_put_contents($newImgNameWithRoot, file($imgRoot)) === false) {
                return '[Error writing file!]';
            }
            $htmlAsStrImg = str_replace($img, $newImgNameWithDir, $htmlAsStr);
        }
    }
//Загружаем и пишем html
    $html = $htmlAsStrImg ?? $htmlAsStr;
    if (file_put_contents($outputNameWithPath . '.html', $html) !== false) {
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
