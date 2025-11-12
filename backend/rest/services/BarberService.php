<?php
require_once "BaseService.php";
require_once __DIR__ . '/../dao/BarberDao.php';

class BarberService extends BaseService {
    public function __construct() {
        parent::__construct(new BarberDao());
    }

    public function get_by_name($name) {
        return $this->dao->getByName($name);
    }
}
?>
