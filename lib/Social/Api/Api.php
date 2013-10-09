<?php

namespace Social\Api;

use Social\Auth\Token;
use Social\Utils;

abstract class Api
{
    private $token;
    private $error;

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return User
     */
    abstract public function getProfile();

    protected function execGet($url, array $data = array())
    {
        return Utils::execGet($url, $data);
    }

    public function getError()
    {
        return $this->error;
    }

    protected function setError($error)
    {
        $this->error = $error;
    }
}