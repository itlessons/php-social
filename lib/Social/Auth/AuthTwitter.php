<?php

namespace Social\Auth;


use Social\Type;
use Social\Utils;

class AuthTwitter extends OAuth2
{
    const AUTH_URL_TW = 'https://github.com/login/oauth/authorize';
    const TOKEN_URL_TW = 'https://github.com/login/oauth/access_token';

    public function __construct($id, $secret, $scope = '')
    {
        parent::__construct(self::AUTH_URL_TW, $id, $secret, self::TOKEN_URL_TW, $scope);
    }

    public function isValidToken($token)
    {
        $token = $this->parseToken($token);
        return !empty($token['access_token']);
    }

    protected function createToken($token)
    {
        $token = $this->parseToken($token);
        return new Token($this->getType(), $token['access_token'], -1, -1);
    }

    public function getType()
    {
        return Type::TWITTER;
    }

    private function parseToken($token)
    {
        if (is_string($token)) {
            return Utils::parseStr($token);
        }

        return $token;
    }
}