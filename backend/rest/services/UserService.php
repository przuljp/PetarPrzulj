<?php
require_once "BaseService.php";
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService {
    public function __construct() {
        parent::__construct(new UserDao());
    }

    public function get_by_email($email) {
        return $this->dao->getByEmail($email);
    }

    public function get_user_by_id($id) {
        return $this->dao->getUserByID($id);
    }
}
?>
