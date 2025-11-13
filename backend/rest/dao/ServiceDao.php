<?php
require_once 'BaseDao.php';

class ServiceDao extends BaseDao {
    public function __construct() {
        parent::__construct("services");
    }

    public function getByName($name) {
        $stmt = $this->connection->prepare("SELECT * FROM services WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>
