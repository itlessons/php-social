<?php

namespace Social\Auth;


use Social\Type;

class AuthMr extends OAuth2
{
    public function getType()
    {
        return Type::MR;
    }

    public function isValidToken($token)
    {
        return parent::isValidToken($token) && !empty($token['x_mailru_vid']);
    }

    protected function createToken($token)
    {
        return new Token($this->getType(), $token['access_token'], $token['x_mailru_vid'], $token['expires_in']);
    }

    protected function getAuthUrl()
    {
        return 'https://connect.mail.ru/oauth/authorize';
    }

    protected function getTokenUrl()
    {
        return 'https://connect.mail.ru/oauth/token';
    }
}