<?php

namespace Social\Auth;

use Social\Error;
use Social\Util\OAuth1Client;
use Social\Util\StorageTempData;
use Social\Utils;

abstract class OAuth1 extends Auth
{
    private static $storage;

    private $consumerKey;
    private $consumerSecret;

    private $requestToken;

    public function __construct($consumerKey, $secret)
    {
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $secret;
        $this->requestToken = self::getStorage()->load($this->getStorageKey());
    }

    public function getConsumerKey()
    {
        return $this->consumerKey;
    }

    public function getConsumerSecret()
    {
        return $this->consumerSecret;
    }

    public function getRequestToken()
    {
        return $this->requestToken;
    }

    public function setRequestToken($requestToken)
    {
        $this->requestToken = $requestToken;
    }

    protected abstract function getAccessTokenUrl();

    protected abstract function getRequestTokenUrl();

    public function requestRequestToken($redirectUri, $force = false)
    {
        if (!$force && $this->requestToken)
            return $this->requestToken;

        $parameters = array(
            'oauth_callback' => $redirectUri,
        );

        $data = $this->execOAuthRequest('GET', $this->getRequestTokenUrl(), $parameters);

        if (false === strpos($data, '&')) {
            $this->setError(Error::createFromRequest($data));
            return null;
        }

        $this->requestToken = Utils::parseStr($data);
        $this->getStorage()->save(self::getStorageKey(), $this->requestToken);
        return $this->requestToken;
    }

    public function getAuthorizeUrl($redirectUri)
    {
        $token = $this->requestRequestToken($redirectUri);

        if ($token == null)
            return;

        return $this->getAuthUrl()
        . '?oauth_token=' . $token['oauth_token'];
    }

    protected function requestAccessToken(array $request, $redirectUri)
    {
        self::getStorage()->delete($this->getStorageKey());

        if (!isset($request['oauth_verifier'])) {
            $this->setError(Error::createFromRequest($request, Error::INVALID_VERIFIER));
            return null;
        }

        $parameters = array(
            'oauth_verifier' => $request['oauth_verifier'],
        );

        $data = $this->execOAuthRequest('GET', $this->getAccessTokenUrl(), $parameters);

        if (false === strpos($data, '&')) {
            $this->setError(Error::createFromRequest($data));
            return null;
        }

        return $data;
    }

    public function isValidToken($token)
    {
        return !empty($token['oauth_token']) && !empty($token['oauth_token_secret']);
    }

    protected function execOAuthRequest($method, $url, array $parameters = array())
    {
        $request = new OAuth1Client(
            $this->getConsumerKey(),
            $this->getConsumerSecret());

        if ($this->requestToken != null) {
            $request->setOAuthToken($this->requestToken['oauth_token'], $this->requestToken['oauth_token_secret']);
        }

        return $request->exec($method, $url, $parameters);
    }

    protected static function getStorage()
    {
        if (null == self::$storage) {
            self::$storage = new StorageTempData();
        }

        return self::$storage;
    }

    public static function setStorage(StorageTempData $storage)
    {
        self::$storage = $storage;
    }

    private function getStorageKey()
    {
        return $this->getType() . '_' . 'oauth_request_token';
    }
}