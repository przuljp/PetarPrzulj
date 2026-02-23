<?php
require_once "BaseService.php";
require_once __DIR__ . '/../dao/AppointmentDao.php';

class AppointmentService extends BaseService {
    public function __construct() {
        parent::__construct(new AppointmentDao());
    }

    public function insert($data) {
        $this->validateAppointmentDate($data);
        return parent::insert($data);
    }

    public function update($id, $data) {
        $this->validateAppointmentDate($data, false);
        return parent::update($id, $data);
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

    private function validateAppointmentDate($data, $isRequired = true) {
        if (!isset($data['appointment_date']) || trim((string)$data['appointment_date']) === '') {
            if ($isRequired) {
                throw new InvalidArgumentException('Appointment date is required.');
            }
            return;
        }

        try {
            $appointmentDate = new DateTimeImmutable($data['appointment_date']);
        } catch (Exception $e) {
            throw new InvalidArgumentException('Invalid appointment date format.');
        }

        $now = new DateTimeImmutable();
        if ($appointmentDate < $now) {
            throw new InvalidArgumentException('Appointment date cannot be in the past.');
        }
    }
}
?>
