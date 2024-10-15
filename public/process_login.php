<?php
session_start();
require_once '../src/Config/database.php'; // Ensure this path is correct
require_once '../src/Repositories/UserRepository.php';
require_once '../src/Models/User.php'; // Include User model if not already included

// Create a database connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Retrieve username and password from POST request
$username = $_POST['username'];
$password = $_POST['password'];

// Create an instance of UserRepository with the database connection
$userRepo = new UserRepository($connection);

// Authenticate the user
$user = $userRepo->authenticate($username, $password);

if ($user) {
    // Store user information in session
    $_SESSION['user_id'] = $user->getUserId(); // Use the correct method
    $_SESSION['username'] = $user->getUsername();
    $_SESSION['role'] = $user->getRole();
    
    // Redirect to the dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // Redirect back to login with an error message
    header("Location: login.php?error=Invalid username or password");
    exit();
}

?>
