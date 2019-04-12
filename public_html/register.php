<?php
require_once '../bootloader.php';

$form = [
    'fields' => [
        'username' => [
            'label' => 'Username',
            'type' => 'text',
            'placeholder' => 'Zirgas69',
            'validate' => [
                'validate_not_empty',
            ]
        ],
        'email' => [
            'label' => 'Email',
            'type' => 'text',
            'placeholder' => 'email@gmail.com',
            'validate' => [
                'validate_not_empty',
                'validate_email',
                'validate_user_exists'
            ]
        ],
        'password' => [
            'label' => 'Password',
            'type' => 'password',
            'placeholder' => '********',
            'validate' => [
                'validate_not_empty'
            ]
        ],
        'password_again' => [
            'label' => 'Password again',
            'type' => 'password',
            'placeholder' => '********',
            'validate' => [
                'validate_not_empty'
            ]
        ],
        'full_name' => [
            'label' => 'Full Name',
            'type' => 'text',
            'placeholder' => 'Ernestas Zidokas',
            'validate' => [
                'validate_not_empty',
                'validate_contains_space',
                'validate_more_4_chars'
            ]
        ],
        'age' => [
            'label' => 'Age',
            'placeholder' => '26',
            'type' => 'number',
            'min' => 0,
            'max' => 999,
            'validate' => [
                'validate_not_empty',
                'validate_is_number',
                'validate_age'
            ]
        ],
        'gender' => [
            'label' => 'Gender',
            'type' => 'select',
            'placeholder' => '',
            'options' => \Core\User\User::getGenderOptions(),
            'validate' => [
                'validate_not_empty',
                'validate_field_select'
            ]
        ],
        'orientation' => [
            'label' => 'Orientation',
            'type' => 'select',
            'placeholder' => '',
            'options' => \Core\User\User::getOrientationOptions(),
            'validate' => [
                'validate_not_empty',
                'validate_field_select'
            ],
        ],
        'account_type' => [
            'label' => 'Account type',
            'type' => 'select',
            'placeholder' => '',
            'options' => \Core\User\User::getAccountTypeOptions(),
            'validate' => [
                'validate_not_empty',
                'validate_field_select'
            ]
        ],
        'photo' => [
            'label' => 'Photo',
            'placeholder' => 'file',
            'type' => 'file',
            'validate' => [
                'validate_file'
            ]
        ],
    ],
    'validate' => [
        'validate_password',
        'validate_form_file'
    ],
    'buttons' => [
        'submit' => [
            'text' => 'Paduoti!'
        ]
    ],
    'callbacks' => [
        'success' => [
            'form_success'
        ],
        'fail' => []
    ]
];

function validate_password(&$safe_input, &$form) {
    if ($safe_input['password'] === $safe_input['password_again']) {
        return true;
    } else {
        $form['error_msg'] = 'Jobans/a tu buhurs/gazele passwordai nesutampa!';
    }
}

function form_success($safe_input, $form) {
    $user = new Core\User\User([
        'username' => $safe_input['username'],
        'email' => $safe_input['email'],
        'password' => $safe_input['password'],
        'full_name' => $safe_input['full_name'],
        'age' => $safe_input['age'],
        'gender' => $safe_input['gender'],
        'orientation' => $safe_input['orientation'],
        'account_type' => $safe_input['account_type'],
        'photo' => $safe_input['photo'],
        'is_active' => true
    ]);

    $db = new Core\FileDB(DB_FILE);
    $repo = new Core\User\Repository($db, TABLE_USERS);
    $repo->insert($user);
}

function validate_form_file(&$safe_input, &$form) {
    $file_saved_url = save_file($safe_input['photo']);
    if ($file_saved_url) {
        $safe_input['photo'] = 'uploads/' . $file_saved_url;

        return true;
    } else {
        $form['error_msg'] = 'Jobans/a tu buhurs/gazele nes failas supistas!';
    }
}

function save_file($file, $dir = 'uploads', $allowed_types = ['image/png', 'image/jpeg', 'image/gif']) {
    if ($file['error'] == 0 && in_array($file['type'], $allowed_types)) {
        $target_file_name = microtime() . '-' . strtolower($file['name']);
        $target_path = $dir . '/' . $target_file_name;
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            return $target_file_name;
        }
    }

    return false;
}

if (!empty($_POST)) {
    $safe_input = get_safe_input($form);
    $form_success = validate_form($safe_input, $form);

    if ($form_success) {
        $success_msg = strtr('User "@username" sėkmingai sukurtas!', [
            '@username' => $safe_input['username']
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
            <a href="logout.php">Register</a>
            <a href="login.php">Register</a>
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