<?php

namespace Core\Database;

class Connection extends \Core\Database\Abstracts\Connection {

    protected $creds;

    public function __construct($creds) {
        $this->setCredentials($creds);
    }

    public function connect() {
        try {
            $this->pdo = new \PDO
                    ("mysql:host=$this->host", $this->user, $this->pass);
            if (DEBUG) {
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXEPTION
                );
                $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true
                );
            }
        } catch (\PDOException $e) {
            throw new \PDOException('Could not connect to the database');
        }
    }

}
