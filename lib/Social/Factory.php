<?php

namespace Social;

use Social\Api\Api;
use Social\Api\ApiFb;
use Social\Api\ApiMr;
use Social\Api\ApiGithub;
use Social\Api\ApiVk;
use Social\Auth\Auth;
use Social\Auth\AuthFb;
use Social\Auth\AuthMr;
use Social\Auth\AuthGithub;
use Social\Auth\AuthVk;
use Social\Auth\Token;

class Factory
{

    private $settings;
    private static $instance;

    public function __construct($settings)
    {
        $this->settings = $this->validateSettings($settings);
        self::$instance = $this;
    }

    /**
     * @param $type
     * @return Auth
     * @throws \InvalidArgumentException
     */
    public function createAuth($type)
    {
        if (!Type::exists($type)) {
            throw new \InvalidArgumentException('Invalid type. Type not exists!');
        }

        if (!isset($this->settings[$type])) {
            throw new \InvalidArgumentException('Invalid type. Settings not set!');
        }

        switch ($type) {
            case Type::VK:
                return new AuthVk(
                    $this->settings[$type]['app_id'],
                    $this->settings[$type]['secret_key'],
                    $this->settings[$type]['scope']
                );
            case Type::MR:
                return new AuthMr(
                    $this->settings[$type]['app_id'],
                    $this->settings[$type]['secret_key'],
                    $this->settings[$type]['scope']
                );
            case Type::FB:
                return new AuthFb(
                    $this->settings[$type]['app_id'],
                    $this->settings[$type]['secret_key'],
                    $this->settings[$type]['scope']
                );
            case Type::GITHUB:
                return new AuthGithub(
                    $this->settings[$type]['app_id'],
                    $this->settings[$type]['secret_key'],
                    $this->settings[$type]['scope']
                );
        }

        throw new \InvalidArgumentException('Auth strategy not implement!');
    }

    /**
     * @param Token $token
     * @return Api
     * @throws \InvalidArgumentException
     */
    public function createApi(Token $token)
    {
        $type = $token->getType();

        switch ($token->getType()) {
            case Type::VK:
                return new ApiVk($token);
            case Type::MR:
                return new ApiMr(
                    $this->settings[$type]['app_id'],
                    $this->settings[$type]['secret_key'],
                    $token
                );
            case Type::FB:
                return new ApiFb($token);
            case Type::GITHUB:
                return new ApiGithub($token);
        }

        throw new \InvalidArgumentException('Api strategy not implement!');
    }

    private function validateSettings($settings)
    {
        foreach ($settings as $type => $data) {
            if (!isset($data['app_id']) || $data['app_id'] <= 0) {
                throw new \InvalidArgumentException(sprintf('Specify app_id for "%s"', Type::getName($type)));
            }

            if (!isset($data['secret_key']) || !$data['secret_key']) {
                throw new \InvalidArgumentException(sprintf('Specify secret_key for "%s"', Type::getName($type)));
            }

            if (!isset($data['scope'])) {
                $settings[$type]['scope'] = '';
            }
        }

        return $settings;
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            throw new \LogicException('Create Factory first');
        }

        return self::$instance;
    }

}