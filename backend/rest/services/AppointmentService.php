<?php
require_once "BaseService.php";
require_once __DIR__ . '/../dao/AppointmentDao.php';

class AppointmentService extends BaseService {
    public function __construct() {
        parent::__construct(new AppointmentDao());
    }

    public function getAppointmentById($id) {
        return $this->dao->getAppointmentById($id);
    }

    public function getAppointmentByUserId($user_id) {
        return $this->dao->getAppointmentByUserId($user_id);
    }

    public function getAppointmentByBarberId($barber_id) {
        return $this->dao->getAppointmentByBarberId($barber_id);
    }
}
?>