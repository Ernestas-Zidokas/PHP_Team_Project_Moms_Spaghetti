<?php
require_once '../bootloader.php';

$form = [
    'fields' => [
        'money' => [
            'label' => '',
            'type' => 'number',
            'required' => true,
            'min' => 5,
            'max' => 50,
            'placeholder' => 'Invesk pinigo skaičių',
            'validate' => [
                'validate_not_empty',
                'validate_is_number'
            ]
        ]
    ],
    'buttons' => [
        'submit' => [
            'text' => 'Įnešti pinigų!'
        ]
    ],
    'validate' => [],
    'callbacks' => [
        'success' => [
        ],
        'fail' => []
    ],
    'errors' => []
];

$cookie = new \Core\Cookie('player');
$player = new \App\Player($cookie);

if (!empty($_POST)) {
    $safe_input = get_safe_input($form);
    $form_success = validate_form($safe_input, $form);
    
    if ($form_success) {
        $player->setBalance(intval($safe_input['money']));
    }
}

$db = new Core\FileDB(DB_FILE);
$repo = new \Core\User\Repository($db, TABLE_USERS);
$session = new Core\User\Session($repo);
?>
<html>
    <head>
        <title>CASINO</title>
        <link rel="stylesheet" href="/css/style.css">
    </head>
    <body>
        <nav>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
            <a href="logout.php">Logout</a>          
            <a href="slot3x3.php">PLAY FOR NOOBS</a>
            <a href="slot5x3.php">PLAY FOR REAL MEN</a>
        </nav>
        <h1>P-OOPINIGU CASINO</h1>
        <?php if ($session->isLoggedIn()): ?>
            <h1>Sveikinu! <?php print $session->getUser()->getUsername(); ?> esi prisijunges</h1>
        <?php else: ?>
            <a href="login.php">Prisijunkite CIA</a>
        <?php endif; ?>
        <?php if ($cookie->exists()): ?>
            <h2>Balansas: <?php print $player->getBalance(); ?>$</h2>
        <?php endif; ?>
        <div class="container">
            <?php require '../core/views/form.php'; ?>
        </div>
    </body>
</html>