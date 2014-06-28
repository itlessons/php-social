<?php

namespace Social\Auth;

class Token
{
    private $type;
    private $accessToken;
    private $identifier;
    private $expiresIn;

    public function __construct($type, $accessToken, $identifier, $expiresIn)
    {
        $this->type = $type;
        $this->accessToken = $accessToken;
        $this->identifier = $identifier;
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

    public function getIdentifier()
    {
        return $this->identifier;
    }
}