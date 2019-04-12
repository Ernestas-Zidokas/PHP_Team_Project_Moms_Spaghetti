<?php
require_once '../bootloader.php';

$form = [
    'fields' => [
        'rap_line' => [
            'label' => 'Repuok',
            'type' => 'text',
            'placeholder' => '',
            'validate' => [
                'validate_not_empty',
                'validate_no_numbers',
                'validate_string_lenght_10_chars',
                'validate_string_lenght_60_chars'
            ]
        ],
    ],
    'validate' => [],
    'buttons' => [
        'submit' => [
            'text' => 'Rap as !'
        ]
    ],
    'callbacks' => [
        'success' => [
            'form_success'
        ],
        'fail' => []
    ]
];


if (!empty($_POST)) {
    $safe_input = get_safe_input($form);
    $form_success = validate_form($safe_input, $form);
}

function form_success($safe_input, $form) {
    
}

$db = new Core\FileDB(DB_FILE);
$repo = new \Core\User\Repository($db, TABLE_USERS);
$session = new Core\User\Session($repo);
?>
<html>
    <head>
        <title>Rap God</title>
        <link rel="stylesheet" href="/css/style.css">
    </head>
    <body>
        <h1>P-OOP MC</h1>
        <?php if ($session->isLoggedIn()): ?>
            <h1>Sveikinu! <?php print $session->getUser()->getUsername(); ?> esi prisijunges</h1>
        <?php else: ?>
            <a href="login.php">Prisijunkite CIA</a>
        <?php endif; ?>
        <div class="container">
            <?php require '../core/views/form.php'; ?>
        </div>
    </body>
</html>