<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Reviewer') {
    header('Location: login.php');
    exit();
}

$billController = new \Src\Controllers\BillController($connection);

// Fetch the bill details
if (isset($_GET['id'])) {
    $billId = $_GET['id'];
    $bill = $billController->getBillById($billId);

    if (!$bill) {
        echo "Bill not found.";
        exit();
    }
} else {
    header('Location: reviewerDashboard.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suggestedTitle = $_POST['suggested_title'];
    $suggestedDescription = $_POST['suggested_description'];
    $comments = $_POST['comments'];
    $reviewerId = $_SESSION['user_id'];

    // Insert the suggested amendment into the database
    $query = "INSERT INTO amendments (bill_id, reviewer_id, suggested_title, suggested_description, comments) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("iisss", $billId, $reviewerId, $suggestedTitle, $suggestedDescription, $comments);
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Amendment suggested successfully!</p>";
    } else {
        echo "<p style='color: red;'>Failed to suggest the amendment.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggest Amendment</title>
</head>
<body>
    <h1>Suggest Amendment</h1>
    <p><strong>Bill Title:</strong> <?php echo htmlspecialchars($bill['title']); ?></p>
    <p><strong>Current Description:</strong> <?php echo htmlspecialchars($bill['description']); ?></p>

    <form method="POST">
        <label for="suggested_title">Suggested Title:</label>
        <input type="text" name="suggested_title" id="suggested_title" required>
        <br>

        <label for="suggested_description">Suggested Description:</label>
        <textarea name="suggested_description" id="suggested_description" required></textarea>
        <br>

        <label for="comments">Comments:</label>
        <textarea name="comments" id="comments"></textarea>
        <br>

        <button type="submit">Submit Amendment</button>
    </form>

    <a href="reviewerDashboard.php">Back to Dashboard</a>
</body>
</html>
