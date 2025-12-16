<?php
require_once "BaseService.php";
require_once __DIR__ . "/../dao/ReviewDao.php";

class ReviewService extends BaseService {
    public function __construct() {
        parent::__construct(new ReviewDao());
    }

    public function getReviewById($review_id) {
        return $this->dao->getReviewById($review_id);
    }

    public function getReviewByUserId($user_id) {
        return $this->dao->getReviewByUserId($user_id);
    }

    public function getReviewByBarberId($barber_id) {
        return $this->dao->getReviewByBarberId($barber_id);
    }
}
?>
