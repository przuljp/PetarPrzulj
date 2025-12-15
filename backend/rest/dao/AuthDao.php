<?php
require_once __DIR__ . '/BaseDao.php';

class AuthDao extends BaseDao {

    public function __construct() {
        // tabela: users, primary key kolona: user_id
        // ako ti se drugačije zove PK, ovdje promijeni
        parent::__construct('users', 'id');
    }

    public function getByEmail($email) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM {$this->table} WHERE email = :email"
        );
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
}
