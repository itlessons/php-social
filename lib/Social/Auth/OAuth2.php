<?php

namespace Social\Auth;

use Social\Error;

abstract class OAuth2 extends Auth
{
    private $id;
    private $secret;

    private $scope;

    public function __construct($id, $secret, $scope)
    {
        $this->id = $id;
        $this->secret = $secret;
        $this->scope = $scope;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    protected abstract function getTokenUrl();

    protected function requestAccessToken(array $request, $redirectUri)
    {
        if (!isset($request['code'])) {
            $this->setError(Error::createFromRequest($request, Error::INVALID_CODE));

            return null;
        }

        $code = (string)$request['code'];

        $parameters = array(
            'client_id' => $this->getId(),
            'client_secret' => $this->getSecret(),
            'redirect_uri' => $redirectUri,
            'code' => $code,
            'grant_type' => 'authorization_code'
        );

        $body = $this->execPost($this->getTokenUrl(), $parameters);
        $data = json_decode($body, true);
        if ($data == null) {
            return $body;
        }

        return $data;
    }

    public function getAuthorizeUrl($redirectUri)
    {
        return $this->getAuthUrl()
        . '?client_id=' . $this->getId()
        . '&scope=' . urlencode($this->getScope())
        . '&state=profile'
        . '&redirect_uri=' . urlencode($redirectUri)
        . '&response_type=code';
    }

    public function isValidToken($token)
    {
        return !empty($token['access_token']) && isset($token['expires_in']);
    }
}