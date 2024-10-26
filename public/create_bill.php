<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Member of Parliament') {
    header('Location: login.php');
    exit();
}

// Include necessary files
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';

// Handle form submission for creating a new bill
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author_id = $_SESSION['user_id']; // Set the logged-in MP's user ID as the author

    // Initialize the BillController
    $billController = new \Src\Controllers\BillController($connection);

    // Set the initial status of the bill
    $status = 'Draft';

    
    if ($billController->createBill($title, $description, $author_id, $status)) {
        // Redirect to the MP Dashboard if successful
        header('Location: mpDashboard.php');
        exit();
    } else {
        // Display error if bill creation fails
        echo "Failed to create the bill. Please try again.";
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
    
    <!-- Form to Create a New Bill -->
    <form method="post" action="">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required><br><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea><br><br>

        <input type="submit" value="Create Bill">
    </form>

    <!-- Link back to MP Dashboard -->
    <a href="mpDashboard.php">Back to Dashboard</a>
</body>
</html>
