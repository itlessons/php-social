<?php

namespace Social\Api;


use Social\Auth\Token;
use Social\SexType;

class ApiMr extends Api
{
    private $profileUrl = 'http://www.appsmail.ru/platform/api';

    private $appId;
    private $appSecret;

    public function __construct($appId, $appSecret, Token $token)
    {
        parent::__construct($token);

        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function getProfile()
    {
        $token = $this->getToken();

        $parameters = array(
            'client_id' => $this->appId,
            'uids' => $token->getUserId(),
            'secure' => '1',
            'method' => 'users.getInfo',
            'session_key' => $token->getAccessToken(),
        );

        $parameters['sig'] = $this->generateSig($parameters);

        $body = $this->execGet($this->profileUrl, $parameters);
        $data = json_decode($body, true);

        if (isset($data[0])) {
            $data = $data[0];
        }

        if (!isset($data['uid'])) {
            $this->setError('wrong_response');

            return null;
        }

        return $this->createUser($data);
    }

    protected function createUser($data)
    {
        $user = new User();
        $user->id = $data['uid'];
        $user->firstName = $data['first_name'];
        $user->lastName = $data['last_name'];
        $user->nickname = $data['nick'];
        $user->email = $data['email'];
        $user->screenName = $data['nick'];
        $user->profileUrl = $data['link'];
        $user->photoUrl = $data['pic_small'];
        $user->photoBigUrl = $data['pic_big'];
        $user->sex = $data['sex'] == 0 ? SexType::MALE : SexType::FEMALE;

        if (isset($data['birthday'])) {
            $user->birthDate = date('Y-m-d', strtotime($data['birthday']));
        }

        $user->info = $data;

        return $user;
    }

    private function generateSig($parameters)
    {
        ksort($parameters);
        $params = '';
        foreach ($parameters as $key => $value) {
            $params .= $key . '=' . $value;
        }

        return md5($params . $this->appSecret);
    }
}