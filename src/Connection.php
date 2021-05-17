<?php

namespace Downloader\Downloader;

class Connection
{
    public string $url;
    public const HTTPSTATUSCODES = [
        0 => 'Unreachable address', //сам добавил интерпретацию
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing', // WebDAV; RFC 2518
        103 => 'Early Hints', // RFC 8297
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information', // since HTTP/1.1
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content', // RFC 7233
        207 => 'Multi-Status', // WebDAV; RFC 4918
        208 => 'Already Reported', // WebDAV; RFC 5842
        226 => 'IM Used', // RFC 3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found', // Previously "Moved temporarily"
        303 => 'See Other', // since HTTP/1.1
        304 => 'Not Modified', // RFC 7232
        305 => 'Use Proxy', // since HTTP/1.1
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect', // since HTTP/1.1
        308 => 'Permanent Redirect', // RFC 7538
        400 => 'Bad Request',
        401 => 'Unauthorized', // RFC 7235
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required', // RFC 7235
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed', // RFC 7232
        413 => 'Payload Too Large', // RFC 7231
        414 => 'URI Too Long', // RFC 7231
        415 => 'Unsupported Media Type', // RFC 7231
        416 => 'Range Not Satisfiable', // RFC 7233
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot', // RFC 2324, RFC 7168
        421 => 'Misdirected Request', // RFC 7540
        422 => 'Unprocessable Entity', // WebDAV; RFC 4918
        423 => 'Locked', // WebDAV; RFC 4918
        424 => 'Failed Dependency', // WebDAV; RFC 4918
        425 => 'Too Early', // RFC 8470
        426 => 'Upgrade Required',
        428 => 'Precondition Required', // RFC 6585
        429 => 'Too Many Requests', // RFC 6585
        431 => 'Request Header Fields Too Large', // RFC 6585
        451 => 'Unavailable For Legal Reasons', // RFC 7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates', // RFC 2295
        507 => 'Insufficient Storage', // WebDAV; RFC 4918
        508 => 'Loop Detected', // WebDAV; RFC 5842
        510 => 'Not Extended', // RFC 2774
        511 => 'Network Authentication Required', // RFC 6585
        218 => 'This is fine', // Apache Web Server
        419 => 'Page Expired', // Laravel Framework
        420 => 'Method Failure', // Spring Framework
        430 => 'Request Header Fields Too Large', // Shopify
        450 => 'Blocked by Windows Parental Controls', // Microsoft
        498 => 'Invalid Token', // Esri
        509 => 'Bandwidth Limit Exceeded', // Apache Web Server/cPanel
        526 => 'Invalid SSL Certificate', // Cloudflare and Cloud Foundry's gorouter
        529 => 'Site is overloaded', // Qualys in the SSLLabs
        530 => 'Site is frozen', // Pantheon web platform
        598 => 'Network read timeout error', // Informal convention
        440 => 'Login Time-out', // IIS
        449 => 'Retry With', // IIS
        444 => 'No Response', // nginx
        494 => 'Request header too large', // nginx
        495 => 'SSL Certificate Error', // nginx
        496 => 'SSL Certificate Required', // nginx
        497 => 'HTTP Request Sent to HTTPS Port', // nginx
        499 => 'Client Closed Request', // nginx
        520 => 'Web Server Returned an Unknown Error', // Cloudflare
        521 => 'Web Server Is Down', // Cloudflare
        522 => 'Connection Timed Out', // Cloudflare
        523 => 'Origin Is Unreachable', // Cloudflare
        524 => 'A Timeout Occurred', // Cloudflare
        525 => 'SSL Handshake Failed', // Cloudflare
        527 => 'Railgun Error', // Cloudflare
    ];

    public function __construct(string $url)
    {
        $this->url = (str_ends_with($url, '/')) ? mb_substr($url, 0, strlen($url) - 1) : $url;
    }
    public function isUrl(): bool
    {
        // Проверка правильности URL
        return (filter_var($this->url, FILTER_VALIDATE_URL) !== false);
    }
    public function getHttpCode(): array
    {
        $headers = array(
            'cache-control: max-age=0',
            'upgrade-insecure-requests: 1',
            'user-agent: Mozilla/5.0 (Windows NT 6.1) ' .
            'AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Safari/537.36',
            'sec-fetch-user: ?1',
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,' .
            'image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
            'x-compress: null',
            'sec-fetch-site: none',
            'sec-fetch-mode: navigate',
            'accept-encoding: deflate, br',
            'accept-language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
        );
        $request = curl_init($this->url);
        if ($request === false) {
            return [0, ''];
        }
        curl_setopt($request, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt');
        curl_setopt($request, CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt');
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($request, CURLOPT_HEADER, true);
        curl_exec($request);
        $httpCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        if (file_exists(__DIR__ . '/cookie.txt')) {
            unlink(__DIR__ . '/cookie.txt');
        }
        $httpStatusCode = (array_key_exists($httpCode, self::HTTPSTATUSCODES))
            ? [$httpCode, self::HTTPSTATUSCODES[$httpCode]]
            : [$httpCode, ''];

        return $httpStatusCode;
    }
}
