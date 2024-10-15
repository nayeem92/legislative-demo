<?php
// Database configuration
define('DB_HOST', 'localhost'); // Change if needed
define('DB_USER', 'root'); // Default XAMPP MySQL username
define('DB_PASSWORD', ''); // Default XAMPP MySQL password (leave it blank)
define('DB_NAME', 'legislation_system_demo'); // Your database name

// Create a database connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
