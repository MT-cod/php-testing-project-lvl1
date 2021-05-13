<?php

namespace PL;

class Connection
{
    public string $url;
    private const HEADERS = array(
        'cache-control: max-age=0',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) 
        Chrome/78.0.3904.97 Safari/537.36',
        'sec-fetch-user: ?1',
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,
        */*;q=0.8,application/signed-exchange;v=b3',
        'x-compress: null',
        'sec-fetch-site: none',
        'sec-fetch-mode: navigate',
        'accept-encoding: deflate, br',
        'accept-language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
    );

    public function __construct(string $url)
    {
        $this->url = (str_ends_with($url, '/')) ? mb_substr($url, 0, strlen($url) - 1) : $url;
    }
    public function isUrl(): bool
    {
        // Проверка правильности URL
        return !!filter_var($this->url, FILTER_VALIDATE_URL);
    }
    public function getHttpCode(): int
    {
        $request = curl_init($this->url);
        curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($request, CURLOPT_HEADER, true);
        curl_setopt($request, CURLOPT_NOBODY, true);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        $resource = curl_exec($request);
        $httpCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        return $httpCode;
    }
    public function getResource(): string
    {
        $request = curl_init($this->url);
        curl_setopt($request, CURLOPT_HTTPHEADER, self::HEADERS);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($request, CURLOPT_HEADER, true);
        $resource = curl_exec($request);
        curl_close($request);
        return $resource;
    }
}
