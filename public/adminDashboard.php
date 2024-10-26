<?php
session_start();

// Include the database and controller files with the correct paths
require_once __DIR__ . '/../src/Config/database.php';
require_once __DIR__ . '/../src/Controllers/BillController.php';

use Src\Controllers\BillController; // Make sure the namespace is correct

// Check if the user is logged in and if the user has the Administrator role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header('Location: login.php');
    exit();
}

// Create a connection to the BillController
$billController = new BillController($connection);
$bills = $billController->getAllBills(); // Fetch all bills for the admin to review

// Handle delete request
if (isset($_GET['delete_id'])) {
    $billController->deleteBill($_GET['delete_id']);
    header('Location: adminDashboard.php'); // Redirect to refresh the page after deletion
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
    <a href="process_logout.php">Logout</a>

    <h2>All Bills</h2>
    <a href="view_amendments.php">View Pending Amendments</a>
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
                    <a href="edit_bill.php?id=<?php echo $bill['bill_id']; ?>">Edit</a>
                    <a href="view_bill.php?id=<?php echo $bill['bill_id']; ?>">View</a>
                    <a href="adminDashboard.php?delete_id=<?php echo $bill['bill_id']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this bill?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
