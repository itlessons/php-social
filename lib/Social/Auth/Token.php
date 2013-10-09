<?php

namespace Social\Auth;

class Token
{
    private $type;
    private $accessToken;
    private $userId;
    private $expiresIn;

    public function __construct($type, $accessToken, $userId, $expiresIn)
    {
        $this->type = $type;
        $this->accessToken = $accessToken;
        $this->userId = $userId;
        $this->expiresIn = $expiresIn;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getExpiresIn()
    {
        return $this->expires_in;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}