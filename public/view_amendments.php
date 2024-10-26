<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Administrator', 'Member of Parliament'])) {
    header('Location: login.php');
    exit();
}

// Create BillController instance
$billController = new \Src\Controllers\BillController($connection);

// Fetch all pending amendments
$query = "SELECT a.*, b.title AS bill_title, u.username AS reviewer FROM amendments a
          JOIN bills b ON a.bill_id = b.bill_id
          JOIN users u ON a.reviewer_id = u.user_id
          WHERE a.status = 'Pending'";
$result = $connection->query($query);
$amendments = $result->fetch_all(MYSQLI_ASSOC);

// Handle Accept/Reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amendmentId = $_POST['amendment_id'];
    $action = $_POST['action'];

    if ($action === 'Accept') {
        // Update the status of the amendment to 'Accepted'
        $query = "UPDATE amendments SET status = 'Accepted' WHERE amendment_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $amendmentId);
        $stmt->execute();

        // Apply the accepted amendment to the bill
        $billController->applyAmendment($amendmentId);

    } elseif ($action === 'Reject') {
        // Update the status of the amendment to 'Rejected'
        $query = "UPDATE amendments SET status = 'Rejected' WHERE amendment_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $amendmentId);
        $stmt->execute();
    }

    // Refresh the page to show updated amendments
    header('Location: view_amendments.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Amendments</title>
</head>
<body>
    <h1>Pending Amendments</h1>
    <?php if ($_SESSION['role'] === 'Member of Parliament'): ?>
        <a href="mpDashboard.php">Back to Dashboard</a>
    <?php elseif ($_SESSION['role'] === 'Administrator'): ?>
        <a href="adminDashboard.php">Back to Dashboard</a>
    <?php endif; ?>

    <table border="1">
        <tr>
            <th>Bill Title</th>
            <th>Suggested Title</th>
            <th>Suggested Description</th>
            <th>Reviewer</th>
            <th>Comments</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($amendments as $amendment): ?>
            <tr>
                <td><?php echo htmlspecialchars($amendment['bill_title']); ?></td>
                <td><?php echo htmlspecialchars($amendment['suggested_title']); ?></td>
                <td><?php echo htmlspecialchars($amendment['suggested_description']); ?></td>
                <td><?php echo htmlspecialchars($amendment['reviewer']); ?></td>
                <td><?php echo htmlspecialchars($amendment['comments']); ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="amendment_id" value="<?php echo $amendment['amendment_id']; ?>">
                        <button type="submit" name="action" value="Accept">Accept</button>
                        <button type="submit" name="action" value="Reject">Reject</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
