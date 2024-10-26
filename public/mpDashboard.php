<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Member of Parliament') {
    header('Location: login.php');
    exit();
}

$billController = new \Src\Controllers\BillController($connection);
$bills = $billController->getBillsByAuthor($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MP Dashboard</title>
</head>
<body>
    <h1>MP Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <a href="process_logout.php">Logout</a>

    <h2>Your Bills</h2>
    <a href="view_amendments.php">View Pending Amendments</a>
    <a href="create_bill.php">Create New Bill</a>
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
        </td>
    </tr>
<?php endforeach; ?>

    </table>
</body>
</html>
