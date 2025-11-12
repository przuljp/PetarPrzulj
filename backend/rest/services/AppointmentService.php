<?php
require_once "BaseService.php";
require_once __DIR__ . '/../dao/AppointmentDao.php';

class AppointmentService extends BaseService {
    public function __construct() {
        parent::__construct(new AppointmentDao());
    }

    public function get_appointment_by_id($id) {
        return $this->dao->getAppointmentByID($id);
    }

    public function get_appointment_by_user_id($user_id) {
        return $this->dao->getAppointmentByUserId($user_id);
    }

    public function get_appointment_by_barber_id($barber_id) {
        return $this->dao->getAppointmentByBarberId($barber_id);
    }
}
?>
