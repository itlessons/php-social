<?php

namespace Social\Api;


use Social\SexType;

class ApiFb extends Api
{

    private $profileUrl = 'https://graph.facebook.com/me';

    public function getProfile()
    {
        $token = $this->getToken();

        $parameters = array(
            'access_token' => $token->getAccessToken(),
            'fields' => 'id,name,first_name,last_name,link,username,gender,picture,birthday',
        );

        $body = $this->execGet($this->profileUrl, $parameters);
        $data = json_decode($body, true);


        if (isset($data['error'])) {
            $this->setError($data['error']);

            return;
        }

        if (!isset($data['id'])) {
            $this->setError('wrong_response');

            return null;
        }

        $user = new User();
        $user->id = $data['id'];
        $user->firstName = $data['first_name'];
        $user->lastName = $data['last_name'];
        $user->nickname = $data['username'];
        $user->screenName = $data['username'];
        $user->profileUrl = $data['link'];
        $user->photoUrl = $data['picture']['data']['url'];
        $user->photoBigUrl = $data['picture']['data']['url'];
        $user->sex = $data['gender'] == 'female' ? SexType::FEMALE : SexType::MALE;

        if (isset($data['birthday'])) {
            $user->birthDate = date('Y-m-d', strtotime($data['birthday']));
        }

        return $user;
    }
}