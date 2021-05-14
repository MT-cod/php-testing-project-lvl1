<?php

namespace PL;

class Connection
{
    public string $url;

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
        curl_exec($request);
        $httpCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        return $httpCode;
    }
}
