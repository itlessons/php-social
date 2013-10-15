<?php

require __DIR__ . '/bootstrap.php';

if (isset($_SESSION['auth'])) {
    $_SESSION['auth'] = array();
}

stopAndRedirect('index.php');