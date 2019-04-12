<?php

namespace Core\Model;

Class ModelUser {

    protected $table_name;
    
    /** @var \Core\FileDB */
    protected $db;

    public function __construct(\Core\FileDB $db, $table_name) {
        $this->table_name = $table_name;
        $this->db = $db;
    }

    public function load($email) {
        $data_row = $this->db->getRow($this->table_name, $email);
        if ($data_row) {
            return new \Core\User\User($data_row);
        }
    }

    public function insert(\Core\User\User $user) {
        if (!$this->exists($user)) {
            $this->db->setRow($this->table_name, $user->getEmail(), $user->getData());
            $this->db->save();

            return true;
        }
    }

    public function update(\Core\User\User $user) {
        if ($this->exists($user)) {
            $this->db->setRow($this->table_name, $user->getEmail(), $user->getData());
            $this->db->save();

            return true;
        }
    }

    public function delete(\Core\User\User $user) {
        if ($this->exists($user)) {
            $this->db->deleteRow($this->table_name, $user->getEmail());
            $this->db->save();

            return true;
        }
    }

    public function loadAll() {
        $user_masyvas = [];
        foreach ($this->db->getRows($this->table_name) as $user) {
            $user_masyvas[] = new \Core\User\User($user);
        }

        return $user_masyvas;
    }

    public function deleteAll() {
        if ($this->db->deleteRows($this->table_name)) {
            $this->db->save();
            return true;
        }
    }

    public function getCount() {
        $get_count = $this->db->getCount($this->table_name);
        if ($get_count) {
            return $get_count;
        }

        return 0;
    }
    
    public function exists(\Core\User\User $user){
        return $this->db->rowExists($this->table_name, $user->getEmail());
    }

}
