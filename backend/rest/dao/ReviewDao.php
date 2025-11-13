<?php
require_once 'BaseDao.php';

class ReviewDao extends BaseDao {
    public function __construct() {
        parent::__construct("reviews");
    }

    public function getReviewByUserId($userId) {
        $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getReviewByID($review_id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE id = :review_id");
        $stmt->bindParam(':review_id', $review_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getReviewByBarberId($barberId) {
    $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE barber_id = :barber_id");
    $stmt->bindParam(':barber_id', $barberId);
    $stmt->execute();
    return $stmt->fetchAll();
}
}
?>
