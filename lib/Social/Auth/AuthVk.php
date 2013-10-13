<?php

namespace Social\Auth;

use Social\Type;

class AuthVk extends OAuth2
{
    const AUTH_URL_VK = 'https://oauth.vk.com/authorize';
    const TOKEN_URL_VK = 'https://oauth.vk.com/access_token';

    public function __construct($id, $secret, $scope = '')
    {
        parent::__construct(self::AUTH_URL_VK, $id, $secret, self::TOKEN_URL_VK, $scope);
    }

    public function isValidToken($token)
    {
        return parent::isValidToken($token) && !empty($token['user_id']);
    }

    protected function createToken($token)
    {
        return new Token($this->getType(), $token['access_token'], $token['user_id'], $token['expires_in']);
    }

    public function getType()
    {
        return Type::VK;
    }
}