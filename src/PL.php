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
        $htmlAsStrWithImgs = $this->imagesProcessing($this->getImages($this->htmlAsStr));
        $htmlAsStrWithImgsAndScrs = $this->scriptsProcessing($this->getScripts($htmlAsStrWithImgs));
        $htmlAsStrWithImgsAndScrsAndlinks = $this->linksProcessing($this->getLinks($htmlAsStrWithImgsAndScrs));
        $this->writeHtml($htmlAsStrWithImgsAndScrsAndlinks);
    }
    public function imagesProcessing(array $images): string
    {
        if ($images != []) {
            if (!file_exists($this->outputNameWithPath . '_files')) {
                mkdir($this->outputNameWithPath . '_files');
            }
            foreach ($images as $img) {
                if (str_starts_with($img, '/')) {
                    //Если путь до картинок в html указан относительно
                    $newImgName = $this->genSlugName($this->url . $img);
                    $imgRoot = $this->url . $img;
                } else {
                    //Если путь до картинок в html указан с url
                    $newImgName = $this->genSlugName($img);
                    $imgRoot = $img;
                }

                $newImgNameWithDir = $this->outputName . '_files/' . $newImgName;
                $newImgNameWithRoot = $this->outputNameWithPath . '_files/' . $newImgName;
                file_put_contents($newImgNameWithRoot, file($imgRoot));
                return str_replace($img, $newImgNameWithDir, $this->htmlAsStr);
            }
        }
    }
    public function scriptsProcessing(array $scripts): string
    {

    }
    public function linksProcessing(array $links): string
    {

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
        $imgSearch = preg_match_all('/(?<=")[^"]+\.(png|jpg)(?=")/', $htmlAsStr, $images);
        return ($imgSearch > 0) ? $images[0] : [];
    }
    public function getLinks(string $htmlAsStr): array
    {
        $imgSearch = preg_match_all('/(?<=")[^"]+\.(png|jpg)(?=")/', $htmlAsStr, $images);
        return ($imgSearch > 0) ? $images[0] : [];
    }

    public function genSlugName(string $url): string
    {
        $hostParts = explode('.', parse_url($url, PHP_URL_HOST));
        $pathParts = explode('/', parse_url($url, PHP_URL_PATH));
        return implode('-', $hostParts) . implode('-', $pathParts);
    }
}
