<?php

require __DIR__ . '/bootstrap.php';

try {
    $type = _GET('type');
    $auth = \Social\Factory::getInstance()->createAuth($type);
    $url = $auth->getAuthorizeUrl($REDIRECT_URL . '?type=' . $type);
    stopAndRedirect($url);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}