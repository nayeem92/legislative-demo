<?php
session_start();
require_once '../src/Config/database.php'; // Ensure this is the correct path
require_once '../src/Repositories/BillRepository.php'; // Include BillRepository
require_once '../src/Models/User.php'; // Include User model if necessary

// Create a database connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: login.php");
    exit();
}

// Create an instance of BillRepository with the database connection
$billRepo = new BillRepository($connection);

// Fetch all bills
$bills = $billRepo->getAllBills();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <h2>All Bills</h2>
    <table>
        <tr>
            <th>Bill ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($bills as $bill): ?>
        <tr>
            <td><?php echo htmlspecialchars($bill['bill_id']); ?></td>
            <td><?php echo htmlspecialchars($bill['title']); ?></td>
            <td><?php echo htmlspecialchars($bill['status']); ?></td>
            <td>
                <a href="view_bill.php?bill_id=<?php echo $bill['bill_id']; ?>">View</a>
                <a href="edit_bill.php?bill_id=<?php echo $bill['bill_id']; ?>">Edit</a>
                <?php if ($bill['status'] === 'Under Review'): ?>
                    <a href="initiate_voting.php?bill_id=<?php echo $bill['bill_id']; ?>">Initiate Voting</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="create_bill.php">Create New Bill</a>
    <a href="process_logout.php">Logout</a>
</body>
</html>
