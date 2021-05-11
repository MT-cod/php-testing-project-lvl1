<?php

namespace PL;

use Monolog\Handler\AbstractHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

//Головная функция
function pageLoader(string $url, string $outputDir, string $clientClass = ''): string
{
    $logger = new Logger('my_logger');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/my_app.log', Logger::DEBUG));
    $logger->pushHandler(new FirePHPHandler());
    $logger->info('Start');

    $resource = new PL($url, $outputDir, $logger);
    $resource->filesProcessing();
    $htmlPath = $resource->getDownloadedHtmlPath();
    return $htmlPath;
}
