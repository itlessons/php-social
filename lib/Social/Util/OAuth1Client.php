<?php

namespace Social\Util;

use Social\Utils;

class OAuth1Client
{
    private $consumerKey;
    private $consumerSecret;
    private $oauthToken;
    private $oauthSecret;

    public function __construct($consumerKey, $consumerSecret, $oauthToken = null, $oauthSecret = null)
    {
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->setOAuthToken($oauthToken, $oauthSecret);
    }

    public function setOAuthToken($oauthToken, $oauthSecret)
    {
        $this->oauthToken = $oauthToken;
        $this->oauthSecret = $oauthSecret;
    }

    public function exec($method, $url, array $parameters = array())
    {
        $method = strtoupper($method);

        $defaults = array(
            'oauth_version' => '1.0',
            'oauth_nonce' => md5(microtime() . mt_rand()),
            'oauth_timestamp' => time(),
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_signature_method' => 'HMAC-SHA1',
        );

        if ($this->oauthToken != null) {
            $defaults['oauth_token'] = $this->oauthToken;
        }

        $parameters = array_merge($defaults, $parameters);
        $parameters['oauth_signature'] = $this->buildOAuthSignature($method, $url, $parameters);

        $parametersStr = OAuth1Client::buildHttpQuery($parameters);

        return HttpClient::exec($method, $url, $parametersStr);
    }

    private function buildOAuthSignature($method, $url, $parameters)
    {
        $stringParts = array(
            strtoupper($method),
            $url,
            OAuth1Client::buildHttpQuery($parameters)
        );

        $keyParts = array(
            $this->consumerSecret,
            ($this->oauthSecret) ? $this->oauthSecret : ''
        );

        $str = implode('&', OAuth1Client::urlEncode($stringParts));
        $key = implode('&', OAuth1Client::urlEncode($keyParts));

        return base64_encode(hash_hmac('sha1', $str, $key, true));
    }

    public static function urlEncode($input)
    {
        if (is_array($input)) {
            return array_map(array('\Social\Util\OAuth1Client', 'urlEncode'), $input);
        } else if (is_scalar($input)) {
            return str_replace(
                '+',
                ' ',
                str_replace('%7E', '~', rawurlencode($input))
            );
        } else {
            return '';
        }
    }

    public static function buildHttpQuery($params)
    {
        if (!$params) return '';

        $keys = OAuth1Client::urlEncode(array_keys($params));
        $values = OAuth1Client::urlEncode(array_values($params));
        $params = array_combine($keys, $values);

        uksort($params, 'strcmp');

        $pairs = array();
        foreach ($params as $parameter => $value) {
            if (is_array($value)) {

                natsort($value);
                foreach ($value as $duplicate_value) {
                    $pairs[] = $parameter . '=' . $duplicate_value;
                }
            } else {
                $pairs[] = $parameter . '=' . $value;
            }
        }

        return implode('&', $pairs);
    }
}