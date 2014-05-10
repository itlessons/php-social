<?php

namespace Social\Api;

class ApiGithub extends Api
{
    private $profileUrl = 'https://api.github.com/user';


    /**
     * @return User
     */
    public function getProfile()
    {
        $token = $this->getToken();

        $parameters = array(
            'access_token' => $token->getAccessToken(),
        );

        $body = $this->execGet($this->profileUrl, $parameters);

        $data = json_decode($body, true);

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

        if (!empty($data['name'])) {

            if (substr_count($data['name'], ' ') == 1) {
                list($user->firstName, $user->lastName) = explode(' ', $data['name']);
            } else {
                $user->firstName = $data['name'];
            }
        }

        $user->nickname = $data['login'];
        $user->screenName = $data['login'];
        $user->profileUrl = $data['html_url'];
        $user->photoUrl = $data['avatar_url'];
        $user->email = $data['email'];

        return $user;
    }
}