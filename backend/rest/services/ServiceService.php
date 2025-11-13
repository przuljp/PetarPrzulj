<?php
require_once "BaseService.php";
require_once __DIR__ . "/../dao/ServiceDao.php";

class ServiceService extends BaseService {
    public function __construct() {
        parent::__construct(new ServiceDao());
    }

    public function get_by_name($name) {
        return $this->dao->getByName($name);
    }
}
?>
