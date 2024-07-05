<?php

namespace Service;

use Trait\SingletonPatternTrait;

class RequestService
{
    use SingletonPatternTrait;

    protected $requestUri;

    public function getRequestUri(): string
    {
        if ($this->requestUri) {
            return $this->requestUri;
        }

        $queryString = $_SERVER[ 'QUERY_STRING' ] ?? '';
        $requestUri  = rtrim(str_replace('?' . $queryString, '', $_SERVER[ 'REQUEST_URI' ]), '/');
        $requestUrl  = (empty($_SERVER[ 'HTTPS' ]) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$requestUri";

        $requestUri = str_replace(HOME_URL, '', $requestUrl);

        if (empty($requestUri)) {
            $requestUri = '/';
        }

        $this->requestUri = $requestUri;

        return $this->requestUri;
    }
}
