<?php
session_start();

// Adjust the path if necessary, based on your file structure
require_once __DIR__ . '/../src/Controllers/AuthController.php';
require_once __DIR__ . '/../src/Config/database.php'; // Corrected path here

use Src\Controllers\AuthController; // Use the correct namespace

// Create a connection (make sure it's correct)
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
} else {
    echo "Database connection successful.<br>"; // Debugging line
}

$authController = new AuthController($connection);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $authController->login($username, $password);

    if ($user) {
        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on user role
        if ($user['role'] === 'Administrator') {
            header("Location: adminDashboard.php");
        } elseif ($user['role'] === 'Reviewer') {
            header("Location: reviewerDashboard.php");
        } elseif ($user['role'] === 'Member of Parliament') {
            header("Location: mpDashboard.php");
        }
        exit();
    } else {
        echo "Invalid credentials!";
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <label>
        <input type="checkbox" name="remember_me"> Remember Me
    </label>
    <button type="submit">Login</button>
</form>
