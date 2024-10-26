<?php
namespace Repositories;

class BillRepository {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Method to get all bills
    public function getAllBills() {
        $query = "SELECT * FROM bills";
        $result = $this->connection->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Method to get bills by author ID
    public function getBillsByAuthor($authorId) {
        $query = "SELECT * FROM bills WHERE author_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $authorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Method to create a new bill
    public function createBill($title, $description, $authorId, $status) {
        $query = "INSERT INTO bills (title, description, author_id, status) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ssis", $title, $description, $authorId, $status);
        return $stmt->execute();
    }

    // Method to update a bill
    public function updateBill($billId, $title, $description, $status) {
        $query = "UPDATE bills SET title = ?, description = ?, status = ? WHERE bill_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sssi", $title, $description, $status, $billId);
    
        if ($stmt->execute()) {
            // Debugging line to check if the status is correct
            echo "<p style='color: green;'>Debug: Status updated to: $status</p>";
            return true;
        } else {
            // If there's an error, display it
            echo "<p style='color: red;'>Error updating bill: " . $stmt->error . "</p>";
            return false;
        }
    }
    
    // Method to delete a bill (handle foreign key constraint)
    public function deleteBill($billId) {
        // First, delete related entries in the `amendments` table to handle foreign key constraint
        $deleteAmendmentsQuery = "DELETE FROM amendments WHERE bill_id = ?";
        $stmtAmendments = $this->connection->prepare($deleteAmendmentsQuery);
        $stmtAmendments->bind_param("i", $billId);

        if ($stmtAmendments->execute()) {
            // Then, delete related entries in the `votes` table
            $deleteVotesQuery = "DELETE FROM votes WHERE bill_id = ?";
            $stmtVotes = $this->connection->prepare($deleteVotesQuery);
            $stmtVotes->bind_param("i", $billId);

            if ($stmtVotes->execute()) {
                // Finally, delete the bill from the `bills` table
                $query = "DELETE FROM bills WHERE bill_id = ?";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param("i", $billId);
                return $stmt->execute();
            } else {
                // If there's an error with deleting related votes, display it
                echo "<p style='color: red;'>Error deleting related votes: " . $stmtVotes->error . "</p>";
                return false;
            }
        } else {
            // If there's an error with deleting related amendments, display it
            echo "<p style='color: red;'>Error deleting related amendments: " . $stmtAmendments->error . "</p>";
            return false;
        }
    }

    // Method to get a single bill by its ID
    public function getBillById($billId) {
        $query = "SELECT * FROM bills WHERE bill_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $billId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
