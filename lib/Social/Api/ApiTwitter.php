<?php

namespace Social\Api;


use Social\Auth\Token;
use Social\Util\OAuth1Client;

class ApiTwitter extends Api
{
    private $apiUrl = 'https://api.twitter.com/1.1/';

    private $consumerKey;
    private $consumerSecret;

    public function __construct($consumerKey, $consumerSecret, Token $token)
    {
        parent::__construct($token);

        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
    }

    public function getProfile()
    {
        $token = $this->getToken();

        $response = $this->execApi('users/lookup', array(
            'screen_name' => $token->getIdentifier(),
        ));

        if ($response == null || !isset($response[0])) {
            $this->setError('wrong_response');
            return null;
        }

        return $this->createUser($response[0]);
    }

    protected function createUser($data)
    {
        $user = new User();
        $user->id = $data['id'];

        if (!empty($data['name'])) {

            if (substr_count($data['name'], ' ') == 1) {
                list($user->firstName, $user->lastName) = explode(' ', $data['name']);
            } else {
                $user->firstName = $data['name'];
            }
        }

        $user->nickname = $data['screen_name'];
        $user->screenName = $data['screen_name'];
        $user->profileUrl = 'http://twitter.com/' . $user->screenName;
        $user->photoUrl = $data['profile_image_url'];
        $user->info = $data;

        return $user;
    }

    private function execApi($apiMethod, array $parameters = array())
    {
        $token = $this->getToken();
        $accessToken = $token->getAccessToken();
        $client = new OAuth1Client(
            $this->consumerKey,
            $this->consumerSecret,
            $accessToken[0],
            $accessToken[1]);

        $url = $this->apiUrl . $apiMethod . '.json';
        $data = $client->exec('GET', $url, $parameters);

        $response = json_decode($data, true);
        if ($response === null)
            return $data;
        return $response;
    }
}