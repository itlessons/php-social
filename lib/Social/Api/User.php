<?php

namespace Social\Api;

use Social\SexType;

class User
{
    public $id;
    public $firstName;
    public $lastName;
    public $nickname;
    public $screenName;
    public $profileUrl;
    public $photoUrl;
    public $photoBigUrl;
    public $birthDate;
    public $email;
    public $sex = SexType::NONE;
    public $info;
}