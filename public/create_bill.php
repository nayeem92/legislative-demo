<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Repositories/BillRepository.php';

// Check if the user is logged in and is an Administrator
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: login.php");
    exit();
}

// Create a database connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author_id = $_SESSION['user_id']; // Assuming the author is the logged-in user
    $status = 'Draft'; // Initial status for a new bill

    // Instantiate the BillRepository to handle bill data
    $billRepo = new BillRepository($connection);

    // Create a new bill in the database
    if ($billRepo->createBill($title, $description, $author_id, $status)) {
        header("Location: dashboard.php"); // Redirect back to the dashboard after creating
        exit();
    } else {
        echo "Failed to create the bill.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Bill</title>
</head>
<body>
    <h1>Create New Bill</h1>
    <form method="post" action="">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>
        
        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>
        
        <input type="submit" value="Create Bill">
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
