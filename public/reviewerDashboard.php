<?php
session_start();

// Include the database and controller files with the correct paths
require_once __DIR__ . '/../src/Config/database.php';
require_once __DIR__ . '/../src/Controllers/BillController.php';

use Src\Controllers\BillController; // Use the correct namespace

// Check if the user is logged in and if the user has the Reviewer role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Reviewer') {
    header('Location: login.php');
    exit();
}

// Create a connection to the BillController
$billController = new BillController($connection);
$bills = $billController->getAllBills(); // Fetch all bills for the reviewer to review
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviewer Dashboard</title>
</head>
<body>
    <h1>Reviewer Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
    <a href="process_logout.php">Logout</a>

    <h2>All Bills</h2>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($bills as $bill): ?>
            <tr>
                <td><?php echo htmlspecialchars($bill['title']); ?></td>
                <td><?php echo htmlspecialchars($bill['description']); ?></td>
                <td><?php echo htmlspecialchars($bill['status']); ?></td>
                <td>
                    <a href="suggest_amendment.php?id=<?php echo $bill['bill_id']; ?>">Amend</a>
                    <a href="view_bill.php?id=<?php echo $bill['bill_id']; ?>">View</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
