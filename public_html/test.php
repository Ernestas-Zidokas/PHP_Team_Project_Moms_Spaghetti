<?php
require_once '../bootloader.php';

$connection = new \Core\Database\Connection([
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'bananas123'
        ]);

$pdo = $connection->getPDO();
$schema = new \Core\Database\Schema($connection, MY_DB);
$schema->init();

$model_user = new App\Model\Users($connection);
$model_user->insertIfNotExists([
    'email' => 'ernestas.zidokas@gmail.com',
    'password' => 'password123',
    'full_name' => 'Ernestas Zidokas',
    'age' => 26,
    'gender' => 'm'
],[]);

?>
<html>
    <head>
        <title>MY SQL</title>
    </head>
    <body>
        
    </body>
</html>
