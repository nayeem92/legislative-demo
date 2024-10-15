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

// Update bill details if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Here you can add validation if needed

    // Update the bill in the database
    if ($billRepo->updateBill($bill_id, $title, $description, $status)) {
        header("Location: dashboard.php"); // Redirect back to the dashboard after update
        exit();
    } else {
        echo "Failed to update the bill.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bill - <?php echo htmlspecialchars($bill['title']); ?></title>
</head>
<body>
    <h1>Edit Bill</h1>
    <form method="post" action="">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($bill['title']); ?>" required>
        
        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?php echo htmlspecialchars($bill['description']); ?></textarea>
        
        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="Draft" <?= $bill['status'] === 'Draft' ? 'selected' : ''; ?>>Draft</option>
            <option value="Under Review" <?= $bill['status'] === 'Under Review' ? 'selected' : ''; ?>>Under Review</option>
            <option value="Voting" <?= $bill['status'] === 'Voting' ? 'selected' : ''; ?>>Voting</option>
            <option value="Passed" <?= $bill['status'] === 'Passed' ? 'selected' : ''; ?>>Passed</option>
            <option value="Rejected" <?= $bill['status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
            <option value="Amended" <?= $bill['status'] === 'Amended' ? 'selected' : ''; ?>>Amended</option>
        </select>
        
        <input type="submit" value="Update Bill">
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
