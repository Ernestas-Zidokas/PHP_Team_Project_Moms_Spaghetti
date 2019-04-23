<?php

class Model extends \Core\Database\Abstracts\Model {

    public function create() {
        $SQL_columns = [];

        foreach ($this->fields as $field) {
            $SQL_columns[] = strtr('@col_name @col_type @col_flags', [
                '@col_name' => SQLBuilder::column($field['name']),
                '@col_type' => $field['type'],
                '@col_flags' => isset($field['flags']) ? implode(' ', $field['flags']) : ''
            ]);
        }

        $sql = strtr('CREATE TABLE @table_name (@columns);', [
            '@table_name' => SQLBuilder::table($this->table_name),
            '@columns' => implode(', ', $SQL_columns)
        ]);

        try {
            return $this->pdo->exec($sql);
        } catch (PDOException $e) {
            throw new Exception('Nesusikure table');
        }
    }

    public function insert($row) {
        $row_keys = array_keys($row);
        $sql = strtr("INSERT INTO @table (@col) VALUES (@val)", [
            '@table' => SQLBuilder::table($this->table_name),
            '@col' => SQLBuilder::columns($row_keys),
            '@val' => SQLBuilder::binds($row_keys)
        ]);
        $query = $this->pdo->prepare($sql);

        foreach ($row as $key => $value) {
            $query->bindValue(SQLBuilder::bind($key), $value);
        }

        try {
            $query->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $ex) {
            throw new Exception('Nepavyko insertint i table');
        }
    }

    /**
     * Inserts $row (array of values) into the table
     * if row does not exist already
     * 
     * @param $row array Row to insert
     * @param $unique_columns array of indexes of unique columns 
     * @throws Exception
     */
    public function insertIfNotExists($row, $unique_columns) {
        if (!$this->load($unique_columns)) {
            $this->insert($row);
            return true;
        }
    }

    public function update($row = array(), $conditions = array()) {
        
    }

}
