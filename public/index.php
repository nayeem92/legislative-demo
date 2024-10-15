<?php
session_start();

// If the user is already logged in, redirect to the appropriate dashboard
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Administrator') {
    header("Location: dashboard.php");
    exit();
} else {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}
?>
