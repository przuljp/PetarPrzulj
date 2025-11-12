<?php
require_once "BaseService.php";
require_once __DIR__ . "/../dao/ReviewDao.php";

class ReviewService extends BaseService {
    public function __construct() {
        parent::__construct(new ReviewDao());
    }

    public function get_review_by_id($review_id) {
        return $this->dao->getReviewByID($review_id);
    }

    public function get_review_by_user_id($user_id) {
        return $this->dao->getReviewByUserId($user_id);
    }

    public function get_review_by_barber_id($barber_id) {
        return $this->dao->getReviewByBarberId($barber_id);
    }
}
?>
