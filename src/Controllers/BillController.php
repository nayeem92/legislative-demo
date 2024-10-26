<?php
namespace Src\Controllers;

require_once __DIR__ . '/../Repositories/BillRepository.php';

use Repositories\BillRepository;

class BillController {
    private $billRepository;
    private $connection; // Store the database connection

    public function __construct($connection) {
        $this->billRepository = new BillRepository($connection);
        $this->connection = $connection; // Store the connection
    }

    // Method to retrieve all bills
    public function getAllBills() {
        return $this->billRepository->getAllBills();
    }

    // Method to retrieve bills by a specific author
    public function getBillsByAuthor($authorId) {
        return $this->billRepository->getBillsByAuthor($authorId);
    }

    // Method to create a new bill
    public function createBill($title, $description, $authorId, $status) {
        return $this->billRepository->createBill($title, $description, $authorId, $status);
    }

    // Method to edit/update a bill
    public function updateBill($billId, $title, $description, $status) {
        return $this->billRepository->updateBill($billId, $title, $description, $status);
    }

    // Method to delete a bill
    public function deleteBill($billId) {
        return $this->billRepository->deleteBill($billId);
    }

    // Method to retrieve a single bill by its ID
    public function getBillById($billId) {
        return $this->billRepository->getBillById($billId);
    }

    // applyAmendment method to ensure changes are reflected in DB
    public function applyAmendment($amendmentId) {
        // Retrieve the amendment details
        $query = "SELECT * FROM amendments WHERE amendment_id = ? AND status = 'Accepted'";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $amendmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $amendment = $result->fetch_assoc();
    
        // If the amendment exists and is accepted
        if ($amendment) {
            // Update the bill with the amendment's suggested title and description
            $updateQuery = "UPDATE bills SET title = ?, description = ? WHERE bill_id = ?";
            $updateStmt = $this->connection->prepare($updateQuery);
            $updateStmt->bind_param("ssi", $amendment['suggested_title'], $amendment['suggested_description'], $amendment['bill_id']);
            
            if ($updateStmt->execute()) {
                echo "<p style='color: green;'>Amendment successfully applied to the bill.</p>";
            } else {
                echo "<p style='color: red;'>Failed to apply amendment to the bill.</p>";
            }
        } else {
            echo "<p style='color: red;'>Amendment not found or not accepted.</p>";
        }
    }
}
