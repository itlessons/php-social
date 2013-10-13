<?php

namespace Social\Auth;

use Social\Error;
use Social\Utils;

abstract class Auth
{
    /**
     * @var AuthToken
     */
    private $token;

    private $authUrl;

    /**
     * @var Error
     */
    private $error;

    public function __construct($authUrl)
    {
        $this->authUrl = $authUrl;
    }

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
