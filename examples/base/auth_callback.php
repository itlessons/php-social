<?php

require __DIR__ . '/bootstrap.php';


try {

    $type = _GET('type');
    $auth = \Social\Factory::getInstance()->createAuth($type);
    $token = $auth->authenticate($_REQUEST, $REDIRECT_URL . '?type=' . $type);

    if (!$token) {
        echo 'Error(auth): ' . $auth->getError();
        exit;
    }

    $_SESSION['auth'] = $token;
    stopAndRedirect('index.php');

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}