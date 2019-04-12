<?php
require_once '../bootloader.php';

redirect();

$form = [
    'fields' => [
    ],
    'buttons' => [
        'submit' => [
            'text' => 'Logout!'
        ]
    ],
    'validate' => [
        'validate_logout'
    ],
    'callbacks' => [
        'success' => [
            'form_success'
        ],
        'fail' => []
    ]
];

function redirect() {
    $db = new Core\FileDB(DB_FILE);
    $repo = new \Core\User\Repository($db, TABLE_USERS);
    $session = new Core\User\Session($repo);

    if (!$session->isLoggedIn() === true) {
        header('Location: login.php');
        exit();
    }
}

function form_success($safe_input, $form) {
    header('Location: login.php');
    exit();
}

function validate_logout(&$safe_input, &$form) {
    $db = new Core\FileDB(DB_FILE);
    $repo = new \Core\User\Repository($db, TABLE_USERS);
    $session = new Core\User\Session($repo);

    if ($session->isLoggedIn() === true) {
        $session->logout();

        return true;
    }
}

if (!empty($_POST)) {
    $safe_input = get_safe_input($form);
    $form_success = validate_form($safe_input, $form);

    if ($form_success) {
        $success_msg = 'Sekmingai atsijungete';
    }
}
?>
<html>
    <head>
        <title>OOP</title>
        <link rel="stylesheet" href="/css/style.css">
    </head>
    <body>
        <nav>
            <a href="index.php">Index</a>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
            <a href="slot3x3.php">PLAY FOR NOOBS</a>
            <a href="slot5x3.php">PLAY FOR REAL MEN</a>
        </nav>
        <div class="container">
            <div class="forma">
                <?php require '../core/views/form.php'; ?>
            </div>
        </div>
        <?php if (isset($success_msg)): ?>
            <h3><?php print $success_msg; ?></h3>
        <?php endif; ?>
    </body>
</html>