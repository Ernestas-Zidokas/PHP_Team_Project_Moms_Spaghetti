<?php
require_once '../bootloader.php';

$form = [
    'fields' => [
        'email' => [
            'label' => 'Email',
            'type' => 'text',
            'placeholder' => 'email@gmail.com',
            'validate' => [
                'validate_not_empty',
                'validate_email'
            ]
        ],
        'password' => [
            'label' => 'Password',
            'type' => 'password',
            'placeholder' => '********',
            'validate' => [
                'validate_not_empty'
            ]
        ]
    ],
    'validate' => [
        'validate_login'
    ],
    'buttons' => [
        'submit' => [
            'text' => 'Login!'
        ]
    ],
    'callbacks' => [
        'success' => [
        ],
        'fail' => []
    ]
];

function validate_login(&$safe_input, &$form) {
    $db = new Core\FileDB(DB_FILE);
    $repo = new \Core\User\Repository($db, TABLE_USERS);
    $session = new Core\User\Session($repo);

    $status = $session->login($safe_input['email'], $safe_input['password']);
    switch ($status) {
        case Core\User\Session::LOGIN_SUCCESS:
            return true;
    }

    $form['error_msg'] = 'Blogas Email/Password!';
}

if (!empty($_POST)) {
    $safe_input = get_safe_input($form);
    $form_success = validate_form($safe_input, $form);
    if ($form_success) {
        $success_msg = strtr('User "@username" sėkmingai prisijungei!', [
            '@username' => $safe_input['email']
        ]);
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
            <a href="logout.php">Logout</a>
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