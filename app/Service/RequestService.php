<?php

namespace Service;

use Trait\SingletonPatternTrait;

class RequestService
{
    use SingletonPatternTrait;

    protected $requestUri;

    public function getHost(): string
    {
        return (empty($_SERVER[ 'HTTPS' ]) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
    }

    public function getHomeUrl(): string
    {
        list($prefix,) = explode('/index.php', $_SERVER[ 'PHP_SELF' ]);
        return $this->getHost() . $prefix;
    }

    public function getRequestUrl(): string
    {
        $queryString = $_SERVER[ 'QUERY_STRING' ] ?? '';
        $requestUri  = rtrim(str_replace('?' . $queryString, '', $_SERVER[ 'REQUEST_URI' ]), '/');
        return $this->getHost() . $requestUri;
    }

    public function getRequestUri(): string
    {
        if ($this->requestUri) {
            return $this->requestUri;
        }

        $requestUrl = $this->getRequestUrl();
        $requestUri = str_replace(HOME_URL, '', $requestUrl);

        if (empty($requestUri)) {
            $requestUri = '/';
        }

        $this->requestUri = $requestUri;

        return $this->requestUri;
    }
}
