<?php

namespace Core\Database;

use \Core\Database\SQLBuilder;

class Schema extends \Core\Database\Abstracts\Schema {

    public function __construct(Abstracts\Connection $c, $name) {
        $this->name = $name;
        $this->pdo = $c->getPDO();
        $this->connection = $c;
    }

    public function create() {
        $create = strtr("CREATE DATABASE @schema_name", [
            '@schema_name' => SQLBuilder::column($this->name)
        ]);
        $this->pdo->exec($create);

//        $user = strtr("CREATE USER @user@@host IDENTIFIED BY @pass", [
//            '@user' => SQLBuilder::value($this->connection->getCredentialUser()),
//            '@host' => SQLBuilder::value($this->connection->getCredentialHost()),
//            '@pass' => SQLBuilder::value($this->connection->getCredentialPass())
//        ]);
//        
//        $this->pdo->exec($user);


        $grant = strtr("GRANT ALL ON @schema_name.* TO @user@@host", [
            '@user' => SQLBuilder::value($this->connection->getCredentialUser()),
            '@host' => SQLBuilder::value($this->connection->getCredentialHost()),
            '@schema_name' => SQLBuilder::column($this->name)
        ]);
        
        $this->pdo->exec($grant);

        $flush = 'FLUSH PRIVILEGES';
        $this->pdo->exec($flush);
    }

}
