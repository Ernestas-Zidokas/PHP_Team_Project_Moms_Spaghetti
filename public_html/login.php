<?php
require_once '../bootloader.php';

$form = [
    'pre_validate' => [
        'validate_time_out'
    ],
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
        'success' => [],
        'fail' => []
    ]
];

function validate_time_out(&$safe_input, &$form) {
    $cookie = new \Core\Cookie('cookie_test');
    $cookie_array = $cookie->read();

    if (isset($cookie_array['atempts'])) {
        $cookie_array['atempts'] ++;
    } else {
        $cookie_array['atempts'] = 1;
    }

    if ($cookie_array['atempts'] > 3) {
        $cookie->save($cookie_array, 30);
        $form['error_msg'] = 'Per daug kartu bandei prisijungti palauk 30 sekundžiu!';

        return false;
    }

    $cookie->save($cookie_array);

    return true;
}

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

        header('Location: index.php');
        exit();
    }
}
?>
<html>
    <head>
        <title>OOP</title>
        <link rel="stylesheet" href="/css/style.css">
    </head>
    <body>
        <?php require '../objects/navigation.php'; ?>
        <h1>GET ON STAGE!</h1>
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