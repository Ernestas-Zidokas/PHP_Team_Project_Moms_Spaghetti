<?php

namespace App\Rap;

Class Line {
    
    public $data;
    
    public function __construct($data = null) {
        if (!$data) {
            $this->data = [
                'line' => null
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

    public function setData(array $data) {
        $this->setPassword($data['line'] ?? '');
    }

}
