<?php

namespace Social\Auth;

use Social\Utils;

abstract class Auth
{
    /**
     * @var AuthToken
     */
    private $token;

    private $authUrl;
    private $error;

    public function __construct($authUrl)
    {
        $this->authUrl = $authUrl;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

    public function authenticate(array $request, $redirectUri = null)
    {
        $token = $this->requestAccessToken($request, $redirectUri);

        if ($token == null) {
            return null;
        }

        if (!$this->isValidToken($token)) {
            $this->error = $token ? $token : 'invalid_token';

            return null;
        }

        return $this->token = $this->createToken($token);
    }

    abstract protected function createToken($token);

    abstract public function isValidToken($token);

    abstract protected function requestAccessToken(array $request, $redirectUri);

    abstract public function getAuthorizeUrl($redirectUri);

    abstract public function getType();

    public function getAuthUrl()
    {
        return $this->authUrl;
    }

    public function getToken()
    {
        return $this->token;
    }

    protected function execPost($url, array $data = array())
    {
        return Utils::execPost($url, $data);
    }
}
