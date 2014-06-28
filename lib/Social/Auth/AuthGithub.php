<?php

namespace Social\Auth;


use Social\Type;
use Social\Utils;

class AuthGithub extends OAuth2
{
    public function isValidToken($token)
    {
        return !empty($token['access_token']);
    }

    protected function createToken($token)
    {
        return new Token($this->getType(), $token['access_token'], -1, -1);
    }

    public function getType()
    {
        return Type::GITHUB;
    }

    protected function getAuthUrl()
    {
        return 'https://github.com/login/oauth/authorize';
    }

    protected function getTokenUrl()
    {
        return 'https://github.com/login/oauth/access_token';
    }
}