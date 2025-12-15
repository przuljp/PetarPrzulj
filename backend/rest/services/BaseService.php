<?php
require_once __DIR__ . '/../dao/BaseDao.php';

class BaseService {
    /** @var BaseDao */
    protected $dao;

    public function __construct(BaseDao $dao) {
        $this->dao = $dao;
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        return $this->dao->getById($id);
    }

    public function insert($data) {
        return $this->dao->insert($data);
    }

    // alias, ako negdje koristiš add umjesto insert
    public function add($data) {
        return $this->insert($data);
    }

    public function update($id, $data) {
        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}
