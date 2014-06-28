<?php

namespace Social\Auth;


use Social\Type;

class AuthFb extends OAuth2
{
    public function isValidToken($token)
    {
        return !empty($token['access_token']) && isset($token['expires']);
    }

    protected function createToken($token)
    {
        return new Token($this->getType(), $token['access_token'], -1, $token['expires']);
    }

    public function getType()
    {
        return Type::FB;
    }

    protected function getAuthUrl()
    {
        return 'https://graph.facebook.com/oauth/authorize';
    }

    protected function getTokenUrl()
    {
        return 'https://graph.facebook.com/oauth/access_token';
    }
}