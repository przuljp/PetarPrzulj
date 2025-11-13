<?php
require_once 'BaseDao.php';

class BarberDao extends BaseDao {
    public function __construct() {
        parent::__construct("barbers");
    }

    public function getByName($name) {
        $stmt = $this->connection->prepare("SELECT * FROM barbers WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>
