# php-social

The library to make work with social networks easy.
They can auth with OAuth 2.0 protocol and retrieve user profile info.

## How to use

For example, auth in vk.com

```php
// auth_vk.php
$APP_ID_VK = -1; // app id
$APP_SECRET_VK = 'some secret code';
$APP_SCOPE_VK = ''; //some permissions
$REDIRECT_URL_VK = 'http://domain.ltd/auth_callback_vk.php';

$auth = new \Social\Auth\AuthVk($APP_ID_VK, $APP_SECRET_VK, $APP_SCOPE_VK);
$url = $auth->getAuthorizeUrl($REDIRECT_URL_VK);
stopAndRedirect($url);
```

Now create callback file and get first api call:

```php
// auth_callback_vk.php
$APP_ID_VK = -1; // app id
$APP_SECRET_VK = 'some secret code';
$APP_SCOPE_VK = ''; //some permissions
$REDIRECT_URL_VK = 'http://domain.ltd/auth_callback_vk.php';

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

## Links
[Авторизация с помощью OAuth 2.0 в Вконтакте, Моем мире и Facebook] (http://www.itlessons.info/php/auth-with-oauth2-in-vk-mailru-facebook/)
[Авторизация на вашем сайте с помощью Github] (http://www.itlessons.info/php/auth-with-oauth2-in-github/)


## Author
[itlessons](http://www.itlessons.info) ([@itlessonsinfo](http://twitter.com/itlessonsinfo))