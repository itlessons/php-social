<?php

namespace Social\Auth;

use Social\Type;

class AuthTwitter extends OAuth1
{
    public function isValidToken($token)
    {
        return parent::isValidToken($token) && !empty($token['screen_name']);
    }

    protected function createToken($token)
    {
        return new Token($this->getType(), array($token['oauth_token'], $token['oauth_token_secret']), $token['screen_name'], -1);
    }

    public function getType()
    {
        return Type::TWITTER;
    }

    protected function getRequestTokenUrl()
    {
        return 'https://api.twitter.com/oauth/request_token';
    }

    protected function getAccessTokenUrl()
    {
        return 'https://api.twitter.com/oauth/access_token';
    }

    protected function getAuthUrl()
    {
        return 'https://api.twitter.com/oauth/authorize';
    }
}