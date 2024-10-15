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

// Check if the bill ID is provided in the query string
if (!isset($_GET['bill_id'])) {
    echo "Bill ID is missing.";
    exit();
}

$bill_id = intval($_GET['bill_id']);

// Instantiate the BillRepository to handle bill data
$billRepo = new BillRepository($connection);

// Fetch the bill details
$bill = $billRepo->getBillById($bill_id);

if (!$bill) {
    echo "Bill not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bill - <?php echo htmlspecialchars($bill['title']); ?></title>
</head>
<body>
    <h1>View Bill</h1>

    <h2><?php echo htmlspecialchars($bill['title']); ?></h2>
    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($bill['description'])); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($bill['status']); ?></p>
    <p><strong>Created At:</strong> <?php echo htmlspecialchars($bill['created_at']); ?></p>

    <p><strong>Author ID:</strong> <?php echo htmlspecialchars($bill['author_id']); ?></p>

    <a href="dashboard.php">Back to Dashboard</a> <!-- Updated link -->
</body>
</html>
