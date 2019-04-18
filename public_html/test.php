<?php
require_once '../bootloader.php';

$connection = new \Core\Database\Connection([
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'bananas123'
        ]);

$pdo = $connection->getPDO();

//$value_array = [
//    'email' => '123taskytojas.zidokas@gmail.com',
//    'password' => 'passwordtaskom',
//    'full_name' => 'Ernestas Zidokas',
//    'age' => 26,
//    'gender' => 'm',
//    'photo' => 'uploads/belenkas.jpg'
//];
//
//$columns = array_keys($value_array);
//
//$sql = strtr("INSERT INTO @db.@table (@columns) VALUES (@values)", [
//    '@db' => \Core\Database\SQLBuilder::schema('my_db'),
//    '@table' => \Core\Database\SQLBuilder::table('users'),
//    '@columns' => \Core\Database\SQLBuilder::columns($columns),
//    '@values' => \Core\Database\SQLBuilder::binds($columns),
//        ]);
//
//$query = $pdo->prepare($sql);
//
//foreach ($value_array as $key => $value) {
//    $query->bindValue(\Core\Database\SQLBuilder::bind($key), $value);
//}
//
//$query->execute();

$schema = new Core\Database\Schema($connection, 'new_db');
$schema->create();
?>
<html>
    <head>
        <title>MY SQL</title>
    </head>
    <body>
        
    </body>
</html>
