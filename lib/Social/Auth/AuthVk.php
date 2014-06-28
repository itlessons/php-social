<?php

namespace Social\Auth;

use Social\Type;

class AuthVk extends OAuth2
{
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

    protected function getAuthUrl()
    {
        return 'https://oauth.vk.com/authorize';
    }

    protected function getTokenUrl()
    {
        return 'https://oauth.vk.com/access_token';
    }
}