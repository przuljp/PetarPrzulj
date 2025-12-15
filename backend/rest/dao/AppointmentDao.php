<?php
require_once 'BaseDao.php';

class AppointmentDao extends BaseDao {
    public function __construct() {
        parent::__construct("appointments");
    }

     public function getAppointmentByID($id) {
        $stmt = $this->connection->prepare("SELECT * FROM appointments WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function getAppointmentByUserId($userId) {
        $stmt = $this->connection->prepare("SELECT * FROM appointments WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAppointmentByBarberId($barberId) {
        $stmt = $this->connection->prepare("SELECT * FROM appointments WHERE barber_id = :barber_id");
        $stmt->bindParam(':barber_id', $barberId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>