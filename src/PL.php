<?php

namespace PL;

class PL
{
    public string $url;
    public string $htmlAsStr;
    public string $outputName;
    public string $outPath;
    public string $outputNameWithPath;

    public function __construct(string $url, string $outputDir)
    {
        $this->url = $url;
        $this->htmlAsStr = file_get_contents($url);
        $this->outputName = $this->genSlugName($url);
        $this->outPath = (str_ends_with($outputDir, '/')) ? $outputDir : $outputDir . '/';
        $this->outputNameWithPath = $this->outPath . $this->outputName;
    }

    public function filesProcessing(): void
    {
        $htmlAsStrWithImgs = $this->deepFilePrng($this->getImages($this->htmlAsStr));
        $htmlAsStrWithImgsAndScrs = $this->deepFilePrng($this->getScripts($htmlAsStrWithImgs));
        $htmlAsStrWithImgsAndScrsAndlinks = $this->deepFilePrng($this->getLinks($htmlAsStrWithImgsAndScrs));
        $this->writeHtml($htmlAsStrWithImgsAndScrsAndlinks);
    }
    public function deepFilePrng(array $files): string
    {
        if ($files != []) {
            //Создаём каталог для хранения файлов, если не создан раньше
            if (!file_exists($this->outputNameWithPath . '_files')) {
                mkdir($this->outputNameWithPath . '_files');
            }
            foreach ($files as $file) {
                if (str_starts_with($file, '/')) {
                    //Если путь до файлов в html указан относительно
                    $newFileName = $this->genSlugName($this->url . $file);
                    $fileRoot = $this->url . $file;
                } else {
                    //Если путь до файлов в html указан с url
                    if ($this->checkUrlInHost($file) === false) {
                        //Если файл с чужого домена, то не берём его
                        continue;
                    }
                    $newFileName = $this->genSlugName($file);
                    $fileRoot = $file;
                }
                $newFileNameWithDir = $this->outputName . '_files/' . $newFileName;
                $newFileNameWithRoot = $this->outputNameWithPath . '_files/' . $newFileName;
                file_put_contents($newFileNameWithRoot, file($fileRoot));
                return str_replace($file, $newFileNameWithDir, $this->htmlAsStr);
            }
        }
        return $this->htmlAsStr;
    }

    public function writeHtml(string $htmlAsStr): void
    {
        file_put_contents($this->outputNameWithPath . '.html', $htmlAsStr);
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
        $hostParts = explode('.', parse_url($url, PHP_URL_HOST));
        $pathParts = explode('/', parse_url($url, PHP_URL_PATH));
        return implode('-', $hostParts) . implode('-', $pathParts);
    }

    public function checkUrlInHost(string $urlOfFile): bool
    {
        $hostOfFile = parse_url($urlOfFile, PHP_URL_HOST);
        $host = parse_url($this->url, PHP_URL_HOST);
        return $hostOfFile == $host;
    }
}
