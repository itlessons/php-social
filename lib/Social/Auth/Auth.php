<?php

namespace Social\Auth;

use Social\Error;
use Social\Util\HttpClient;
use Social\Utils;

abstract class Auth
{
    /**
     * @var AuthToken
     */
    private $token;

    /**
     * @var Error
     */
    private $error;

    public function getError()
    {
        return $this->error;
    }

    public function setError(Error $error)
    {
        $this->error = $error;
    }

    public function authenticate(array $request, $redirectUri = null)
    {
        $token = $this->requestAccessToken($request, $redirectUri);

        if ($token == null) {
            return null;
        }

        $token = $this->parseToken($token);

        if (!$this->isValidToken($token)) {
            $this->setError(Error::createFromRequest($request, Error::INVALID_TOKEN));

            return null;
        }

        return $this->token = $this->createToken($token);
    }

    abstract protected function createToken($token);

    abstract public function isValidToken($token);

    abstract protected function requestAccessToken(array $request, $redirectUri);

    abstract public function getAuthorizeUrl($redirectUri);

    abstract public function getType();

    protected abstract function getAuthUrl();

    public function getToken()
    {
        return $this->token;
    }

    protected function execPost($url, $data = array())
    {
        return HttpClient::exec('POST', $url, $data);
    }

    protected function execGet($url, array $data = array())
    {
        return HttpClient::exec('GET', $url, $data);
    }

    protected function parseToken($token)
    {
        if (is_string($token) && false !== strpos($token, '&')) {
            return static::parseStringResponse($token);
        }

        return $token;
    }

    public static function parseStringResponse($str)
    {
        $arr = array();
        $pairs = explode('&', $str);

        foreach ($pairs as $i) {
            list($name, $value) = explode('=', $i, 2);
            if (isset($arr[$name])) {
                if (is_array($arr[$name])) {
                    $arr[$name][] = $value;
                } else {
                    $arr[$name] = array($arr[$name], $value);
                }
            } else {
                $arr[$name] = $value;
            }
        }

        return $arr;
    }
}
