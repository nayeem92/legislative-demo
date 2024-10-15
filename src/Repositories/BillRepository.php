<?php

class BillRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all bills
    public function getAllBills() {
        $query = "SELECT * FROM bills";
        $result = $this->conn->query($query);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            // Handle error (optional)
            return []; // Return an empty array if query fails
        }
    }

    // Get bill by ID
    public function getBillById($bill_id) {
        $query = "SELECT * FROM bills WHERE bill_id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param('i', $bill_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc(); // Return bill data or null if not found
        } else {
            // Handle error (optional)
            return null; // Return null if statement fails to prepare
        }
    }

    // Update bill details
    public function updateBill($bill_id, $title, $description, $status) {
        $query = "UPDATE bills SET title = ?, description = ?, status = ? WHERE bill_id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param('sssi', $title, $description, $status, $bill_id);
            $stmt->execute();
            return $stmt->affected_rows > 0; // Returns true if at least one row was updated
        } else {
            // Handle error (optional)
            return false; // Return false if statement fails to prepare
        }
    }
    

    // Update bill status only
    public function updateBillStatus($bill_id, $status) {
        $query = "UPDATE bills SET status = ? WHERE bill_id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param('si', $status, $bill_id);
            $stmt->execute();
            return $stmt->affected_rows > 0; // Returns true if at least one row was updated
        } else {
            // Handle error (optional)
            return false; // Return false if statement fails to prepare
        }
    }

    // Create a new bill
    public function createBill($title, $description, $author_id, $status) {
        $query = "INSERT INTO bills (title, description, author_id, status) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param('ssis', $title, $description, $author_id, $status);
            $stmt->execute();
            return $stmt->affected_rows > 0; // Returns true if the bill was created successfully
        } else {
            // Handle error (optional)
            return false; // Return false if statement fails to prepare
        }
    }

    public function getBillsByStatus($status) {
        $query = "SELECT * FROM bills WHERE status = ?";
        $stmt = $this->conn->prepare($query);
    
        if ($stmt) {
            $stmt->bind_param('s', $status);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); // Return all bills matching the status
        } else {
            return []; // Return an empty array if the statement fails
        }
    }
    


    
}
?>
