<?php

namespace Social\Auth;


use Social\Type;

class AuthMr extends OAuth2
{
    const AUTH_URL_MR = 'https://connect.mail.ru/oauth/authorize';
    const TOKEN_URL_MR = 'https://connect.mail.ru/oauth/token';

    public function __construct($id, $secret, $scope = '')
    {
        parent::__construct(self::AUTH_URL_MR, $id, $secret, self::TOKEN_URL_MR, $scope);
    }

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
}