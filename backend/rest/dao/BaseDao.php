<?php
require_once __DIR__ . '/../config.php';

class BaseDao {
    protected $table;
    protected $idColumn;
    protected $connection;

    public function __construct($table, $idColumn = 'id') {
        $this->table = $table;
        $this->idColumn = $idColumn;
        $this->connection = Database::connect();
    }

    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM {$this->table} WHERE {$this->idColumn} = :id"
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);

        // ako nema primary key u arrayu, dodaj ga iz lastInsertId
        if (!array_key_exists($this->idColumn, $data)) {
            $data[$this->idColumn] = $this->connection->lastInsertId();
        }

        return $data; // vraćamo kompletan entity, ne samo bool
    }

    public function update($id, $data) {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
        }
        $fieldsSql = implode(", ", $fields);

        $sql = "UPDATE {$this->table} SET {$fieldsSql} WHERE {$this->idColumn} = :id";
        $stmt = $this->connection->prepare($sql);

        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->connection->prepare(
            "DELETE FROM {$this->table} WHERE {$this->idColumn} = :id"
        );
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
