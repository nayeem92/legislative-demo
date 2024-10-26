<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$billController = new \Src\Controllers\BillController($connection);
$billId = $_GET['id'];
$bill = $billController->getBillById($billId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $updated = $billController->updateBill($billId, $title, $description, $status);
    if ($updated) {
        echo "<p style='color: green;'>Bill successfully updated!</p>";
    } else {
        echo "<p style='color: red;'>Failed to update the bill.</p>";
    }

    // Reload the updated bill details
    $bill = $billController->getBillById($billId);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bill</title>
</head>
<body>
    <h1>Edit Bill</h1>

    <!-- Display bill details -->
    <p><strong>Title:</strong> <?php echo htmlspecialchars($bill['title']); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($bill['description']); ?></p>
    <p><strong>Status (submitted):</strong> <?php echo htmlspecialchars($bill['status']); ?></p>
    <p><strong>Bill ID:</strong> <?php echo htmlspecialchars($bill['bill_id']); ?></p>

    <form method="POST">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($bill['title']); ?>" required>
        <br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?php echo htmlspecialchars($bill['description']); ?></textarea>
        <br>

        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <?php if ($_SESSION['role'] === 'Member of Parliament'): ?>
                <!-- MP Role: Only show "Draft" and "Under Review" options -->
                <option value="Draft" <?php if ($bill['status'] === 'Draft') echo 'selected'; ?>>Draft</option>
                <option value="Under Review" <?php if ($bill['status'] === 'Under Review') echo 'selected'; ?>>Under Review</option>
            <?php elseif ($_SESSION['role'] === 'Reviewer'): ?>
                <!-- Reviewer Role: Allow more statuses -->
                <option value="Under Review" <?php if ($bill['status'] === 'Under Review') echo 'selected'; ?>>Under Review</option>
                <option value="Voting" <?php if ($bill['status'] === 'Voting') echo 'selected'; ?>>Voting</option>
            <?php elseif ($_SESSION['role'] === 'Administrator'): ?>
                <!-- Admin Role: Full access to all statuses -->
                <option value="Under Review" <?php if ($bill['status'] === 'Under Review') echo 'selected'; ?>>Under Review</option>
                <option value="Voting" <?php if ($bill['status'] === 'Voting') echo 'selected'; ?>>Voting</option>
                <option value="Passed" <?php if ($bill['status'] === 'Passed') echo 'selected'; ?>>Passed</option>
                <option value="Rejected" <?php if ($bill['status'] === 'Rejected') echo 'selected'; ?>>Rejected</option>
                <option value="Amended" <?php if ($bill['status'] === 'Amended') echo 'selected'; ?>>Amended</option>
            <?php endif; ?>
        </select>
        <br>

        <button type="submit">Update Bill</button>
    </form>

    <!-- Back to Dashboard Link -->
    <?php
        if ($_SESSION['role'] === 'Member of Parliament') {
            echo '<a href="mpDashboard.php">Back to Dashboard</a>';
        } elseif ($_SESSION['role'] === 'Reviewer') {
            echo '<a href="reviewerDashboard.php">Back to Dashboard</a>';
        } elseif ($_SESSION['role'] === 'Administrator') {
            echo '<a href="adminDashboard.php">Back to Dashboard</a>';
        }
    ?>
</body>
</html>
