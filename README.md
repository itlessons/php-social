# php-social

==========

## Brief

-----

The library to make work with social networks easy.
They can auth with OAuth 2.0 or OAuth 1+ protocols and retrieve user profile info.
Support Vkontakte, Facebook, Twitter, Github, MailRu.
Works with PHP 5.3.3 or later.

## Usage

-----

You can see [base example](https://github.com/itlessons/php-social/tree/master/examples/base).

Auth in vk.com:

```php
// config.php
$APP_ID_VK = -1; // app id
$APP_SECRET_VK = 'some secret code';
$APP_SCOPE_VK = ''; //some permissions
$REDIRECT_URL_VK = 'http://domain.ltd/auth_callback_vk.php';

// auth_vk.php
require __DIR__.'/config.php';
$auth = new \Social\Auth\AuthVk($APP_ID_VK, $APP_SECRET_VK, $APP_SCOPE_VK);
$url = $auth->getAuthorizeUrl($REDIRECT_URL_VK);
stopAndRedirect($url);
```

Now create callback file and get first api call:

```php
// auth_callback_vk.php
require __DIR__.'/config.php';
$auth = new \Social\Auth\AuthVk($APP_ID_VK, $APP_SECRET_VK, $APP_SCOPE_VK);
$token = $auth->authenticate($_REQUEST, $REDIRECT_URL_VK);

if($token == null){
    var_dump($auth->getError());
    //exit
}

//call api with access_token
$api = new \Social\Api\ApiVk($token);
$user = $api->getProfile();

//use user data

// $user->id
// $user->firstName
// $user->lastName
// $user->nickname
// $user->screenName
// $user->photoUrl
// $user->photoBigUrl
// ...
```

## Installation
------------

The recommended way to install php-social is through [Composer](http://getcomposer.org). Just create a
`composer.json` file and run the `php composer.phar install` command to
install it:

```json
{
    "require": {
        "itlessons/php-social": "*"
    }
}
```

Or you can use the console command from your project root folder:

```sh
$ composer require itlessons/php-social
```

Alternatively, you can download the [php-social.zip](https://github.com/itlessons/php-social/archive/master.zip) file and extract it.

Read
----

  * [Авторизация с помощью OAuth 2.0 в Вконтакте, Моем мире и Facebook](http://www.itlessons.info/php/auth-with-oauth2-in-vk-mailru-facebook/)
  * [Авторизация на вашем сайте с помощью Github](http://www.itlessons.info/php/auth-with-oauth2-in-github/)
  * [Авторизация и работа с Twitter Api через OAuth](http://www.itlessons.info/php/twitter-oauth-login-and-api/)

