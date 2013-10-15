<?php

ini_set('display_errors', 'on');

spl_autoload_register(
    function ($class) {
        $baseDir = __DIR__ . '/../../lib';
        $path = $baseDir . '/' . str_replace('\\', '/', $class) . '.php';

        if (is_file($path)) {
            require $path;

            return;
        }

        throw new \LogicException(sprintf('Class "%s" not found in "%s"', $class, $path));
    }
);

session_start();

$settingsFile = __DIR__ . '/settings.php';

if (!is_file($settingsFile)) {
    die('Configure settings.php');
}

require $settingsFile;

$SCRIPT = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
$REDIRECT_URL = 'http://' . $_SERVER['HTTP_HOST'] . $SCRIPT . '/auth_callback.php';


function _GET($key)
{
    return isset($_GET[$key]) ? $_GET[$key] : null;
}

function _e($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function _d($str, $default)
{
    return $str ? _e($str) : _e($default);
}

function stopAndRedirect($url)
{
    header('Location: ' . $url);

    $content = sprintf(
        '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="refresh" content="1;url=%1$s" /><title>Redirecting to %1$s</title></head><body>Redirecting to <a href="%1$s">%1$s</a>.</body></html>',
        htmlspecialchars($url, ENT_QUOTES, 'UTF-8')
    );

    echo $content;

    exit;
}