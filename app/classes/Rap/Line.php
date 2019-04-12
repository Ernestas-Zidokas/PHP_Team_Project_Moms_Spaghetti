<?php

namespace App\Rap;

Class Line {

    public $data;

    public function __construct($data = null) {
        if (!$data) {
            $this->data = [
                'line' => null,
                'email' => null
            ];
        } else {
            $this->setData($data);
        }
    }

    public function getLine(): string {
        return $this->data['line'];
    }

    public function setLine(string $line) {
        $this->data['line'] = $line;
    }

    public function setEmail(string $email) {
        $this->data['email'] = $email;
    }

    public function getEmail() {
        return $this->data['email'];
    }

    public function setData(array $data) {
        $this->setLine($data['line'] ?? '');
        $this->setEmail($data['email'] ?? '');
    }

    public function getData() {
        return $this->data;
    }

}
