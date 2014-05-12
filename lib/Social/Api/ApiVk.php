<?php

namespace Social\Api;


class ApiVk extends Api
{

    private $profileUrl = 'https://api.vk.com/method/users.get';

    public function getProfile()
    {
        $token = $this->getToken();

        $parameters = array(
            'user_ids' => $token->getUserId(),
            'access_token' => $token->getAccessToken(),
            'fields' => 'first_name,last_name,nickname,screen_name,sex,photo_50,photo_200_orig,photo_200,bdate',
            'v' => '5.2',
        );


        $body = $this->execGet($this->profileUrl, $parameters);
        $data = json_decode($body, true);

        if (isset($data['error'])) {
            $this->setError($data['error']);

            return;
        }

        if (isset($data['response'][0])) {
            $data = $data['response'][0];
        }

        if (!isset($data['id'])) {
            $this->setError('wrong_response');

            return null;
        }

        return $this->createUser($data);
    }

    protected function createUser($data)
    {
        $user = new User();
        $user->id = $data['id'];
        $user->firstName = $data['first_name'];
        $user->lastName = $data['last_name'];
        $user->nickname = $data['nickname'];
        $user->screenName = $data['screen_name'];
        $user->profileUrl = 'http://vk.com/' . $user->screenName;
        $user->photoUrl = $data['photo_50'];
        $user->photoBigUrl = isset($data['photo_200']) ? $data['photo_200'] : $data['photo_200_orig'];
        $user->sex = $data['sex'];

        if (isset($data['bdate'])) {
            $user->birthDate = date('Y-m-d', strtotime($data['bdate']));
        }

        $user->info = $data;

        return $user;
    }
}