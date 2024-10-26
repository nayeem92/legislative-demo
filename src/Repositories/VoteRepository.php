<?php

class VoteRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function recordVote($billId, $userId, $vote) {
        $sql = "INSERT INTO votes (bill_id, user_id, vote) VALUES (:bill_id, :user_id, :vote)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'bill_id' => $billId,
            'user_id' => $userId,
            'vote' => $vote
        ]);
    }

    public function getResultsByBillId($billId) {
        $sql = "SELECT vote, COUNT(*) as count FROM votes WHERE bill_id = :bill_id GROUP BY vote";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['bill_id' => $billId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
