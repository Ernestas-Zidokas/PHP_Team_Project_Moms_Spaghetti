<?php
require_once '../bootloader.php';

$form = [
    'pre_validate' => [],
    'fields' => [
        'rap_line' => [
            'label' => 'Repuok',
            'type' => 'text',
            'placeholder' => 'Moms spaghetti',
            'validate' => [
                'validate_line_exists',
                'validate_rap_in_a_row',
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
            'text' => 'Rap as @name'
        ]
    ],
    'callbacks' => [
        'success' => [
            'form_success'
        ],
        'fail' => []
    ]
];

function validate_line_exists($field_input, &$field, &$safe_input) {
    $db = new Core\FileDB(DB_FILE);
    $song = new \App\Rap\Song($db, TABLE_LINES);

    foreach ($song->loadAll() as $one_line) {
        if ($one_line->getLine() == $safe_input['rap_line']) {
            $field['error_msg'] = 'Tokia eilute jau existuoja!';

            return false;
        }
    }

    return true;
}

function validate_rap_in_a_row($field_input, &$field, &$safe_input) {
    $db = new Core\FileDB(DB_FILE);
    $song = new \App\Rap\Song($db, TABLE_LINES);
    $repo = new \Core\User\Repository($db, TABLE_USERS);
    $session = new Core\User\Session($repo);

    if ($song->getCount($song->loadAll()) > 0) {
        if ($song->canRap($session->getUser())) {
            return true;
        } else {
            $field['error_msg'] = 'Negali repuoti kol po taves kitas neparepavo!';

            return false;
        }
    }

    return true;
}

function form_success($safe_input, $form) {
    $db = new Core\FileDB(DB_FILE);
    $song = new \App\Rap\Song($db, TABLE_LINES);
    $repo = new \Core\User\Repository($db, TABLE_USERS);
    $session = new Core\User\Session($repo);

    $line = new App\Rap\Line([
        'line' => $safe_input['rap_line'],
        'email' => $session->getUser()->getEmail()
    ]);
    $song->insert($line);
}

if (!empty($_POST)) {
    $safe_input = get_safe_input($form);
    $form_success = validate_form($safe_input, $form);
}

$db = new Core\FileDB(DB_FILE);
$repo = new \Core\User\Repository($db, TABLE_USERS);
$session = new Core\User\Session($repo);
$song = new \App\Rap\Song($db, TABLE_LINES);

if ($session->isLoggedIn()) {
    $form['buttons']['submit']['text'] = strtr($form['buttons']['submit']['text'], [
        '@name' => $session->getUser()->getFullName()
    ]);
}

//$query = $pdo->prepare('INSERT INTO `my_db`.`users` '
//        . '(`email`, `password`, `full_name`, `age`, `gender`, `photo`)'
//        . 'VALUES(:email, :pass, :full_name, :age, :gender, :photo)');
//        
//$query->execute();
//$users = [];
//$last_gender = '';
//
//while ($row = $query->fetch(PDO::FETCH_LAZY)) {
//    $gender = $row['gender']; // Requestas i duombaze
//    if ($gender == $last_gender && $gender == 'f') {
//        break;
//    } else {
//        $last_gender = $gender;
//        $users[] = [
//            'full_name' => $row['full_name'],
//            'age' => $row['age'],
//            'email' => $row['email'],
//            'gender' => $row['gender'],
//            'photo' => $row['photo']
//        ];
//    }
//}
?>
<html>
    <head>
        <title>Rap God</title>
        <link rel="stylesheet" href="/css/style.css">
    </head>
    <body>
        <?php require '../objects/navigation.php'; ?>
        <h1>P-OOP MC</h1>
        <?php if ($session->isLoggedIn()): ?>
            <h1>Sveikinu! <?php print $session->getUser()->getUsername(); ?> esi prisijunges</h1>
            <div class="container">
                <?php require '../core/views/form.php'; ?>
                <p><a href="logout.php">Logout CIA</a></p>
            </div>
        <?php else: ?>
            <span><a href="login.php">Prisijunkite CIA</a></span>
            <span><a href="register.php">Registruotis CIA</a></span>
        <?php endif; ?>
        <?php foreach ($song->loadAll() as $line): ?>
            <h2 class="line"><?php print $line->getLine(); ?>
                <span
                    class="author">Author: <?php print $repo->load($line->getEmail())->getFullName(); ?>
                </span>
            </h2>
        <?php endforeach; ?>
    </body>
</html>
