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

    public function insertIfNotExists($row, $unique_columns) {
        if (!$this->load($unique_columns)) {
            $this->insert($row);
            return true;
        }
    }

    /**
     * Updates $row columns based on conditions
     * 
     * Array index represents column name.
     * Array value represents that column (updated) value.
     * 
     * $row = [
     *          'full_name' => 'Wicked Mthfucka',
     *          'photo' => 'https://i.ytimg.com/vi/uVxSZnJv2gs/maxresdefault.jpg,
     *        ];
     * 
     * $conditions = [
     *          'email' => 'lolz@gmail.com          
     *          ];
     * 
     * Conditions represent WHERE statements, combined with AND
     * 
     * @param $row array Row array
     * @param $conditions array WHERE conditions
     * @throws Exception
     */
    public function update($row = [], $conditions = []) {
        $row_keys = array_keys($row);
        $condition_array = [];

        foreach ($conditions as $condition_idx => $condition) {
            $condition_array[] = strtr('(@index = @condition)', [
                '@index' => Core\Database\SQLBuilder::column($condition_idx),
                '@condition' => Core\Database\SQLBuilder::bind($condition_idx)
            ]);
            $query = $this->pdo->prepare($condition_array);
            $query->bindValue(SQLBuilder::bind($condition_idx), $condition);
        }

        $sql = strtr("UPDATE @table SET @col WHERE @condition", [
            '@table' => SQLBuilder::table($this->table_name),
            '@col' => Core\Database\SQLBuilder::columnEqualBinds($row_keys),
            '@condition' => implode(' AND ', $condition_array),
        ]);

        $query = $this->pdo->prepare($sql);

        foreach ($row as $key => $value) {
            $query->bindValue(SQLBuilder::bind($key), $value);
        }

        try {
            $query->execute();
            return true;
        } catch (PDOException $ex) {
            throw new Exception('Nepavyko update table');
        }
    }

}
