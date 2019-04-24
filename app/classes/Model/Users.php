<?php

namespace App\Model;

class Users extends \Core\Database\Model {

    protected $connection;

    public function __construct(\Core\Database\Connection $conn) {
        parent::__construct($conn, 'users_table', [
            [
                'name' => 'email',
                'type' => self::TEXT_SHORT,
                'flags' => [self::FLAG_NOT_NULL, self::FLAG_PRIMARY]
            ],
            [
                'name' => 'password',
                'type' => self::TEXT_SHORT,
                'flags' => self::FLAG_NOT_NULL
            ],
            [
                'name' => 'full_name',
                'type' => self::TEXT_SHORT,
                'flags' => self::FLAG_NOT_NULL
            ],
            [
                'name' => 'age',
                'type' => self::NUMBER_MED,
                'flags' => self::FLAG_NOT_NULL
            ],
            [
                'name' => 'gender',
                'type' => self::CHAR,
                'flags' => self::FLAG_NOT_NULL
            ],
            [
                'name' => 'photo',
                'type' => self::TEXT_MED,
                'flags' => self::FLAG_NOT_NULL
            ]
        ]);
    }

}
