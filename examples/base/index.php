<?php

require __DIR__ . '/bootstrap.php';

$token = null;
$user = null;
$error = null;

if (isset($_SESSION['auth'])) {
    $token = $_SESSION['auth'];
}

if ($token) {
    $api = \Social\Factory::getInstance()->createApi($token);
    $user = $api->getProfile();

    if (!$user) {
        $error = $api->getError();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="static/main.css">
    <title>Base example</title>
</head>
<body>
<div class="tst">

    <?php if ($error) : ?>
        <div class="error"><?php echo $error ?></div>
    <?php elseif ($user): ?>

        <div class="user-card">
            <img src="<?php echo $user->photoUrl ?>" alt="">

            <div class="user-data">
                Name: <?= _e($user->firstName . ' ' . $user->lastName) ?><br>
                Nickname: <?= _d($user->nickname, 'empty') ?><br>
                Profile: <a href="<?= $user->profileUrl ?>"><?= $user->profileUrl ?></a><br>
            </div>
        </div>

        <div class="sign-out"><a href="signout.php">Выйти</a></div>

    <?php else : ?>
        <a href="auth.php?type=1">Auth by vk.com</a>
        &nbsp;|&nbsp;
        <a href="auth.php?type=2">Auth by mail.ru</a>
        &nbsp;|&nbsp;
        <a href="auth.php?type=3">Auth by facebook</a>
        &nbsp;|&nbsp;
        <a href="auth.php?type=4">Auth by github</a>
        &nbsp;|&nbsp;
        <a href="auth.php?type=5">Auth by twitter</a>
    <?php endif; ?>
</div>
</body>
</html>